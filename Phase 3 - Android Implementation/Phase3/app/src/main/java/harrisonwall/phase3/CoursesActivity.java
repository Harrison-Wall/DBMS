package harrisonwall.phase3;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.RadioButton;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.StringTokenizer;

public class CoursesActivity extends AppCompatActivity
{
    private int accountID, accountType, subType;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.section_list);

        // Get user info
        Intent thisIntent = getIntent();
        accountID   = thisIntent.getIntExtra("ID", -1);
        accountType = thisIntent.getIntExtra("Type", -1);
        subType     = thisIntent.getIntExtra("SubType", -1);

        // If not no info found
        if( accountID < 0 || accountType < 0 || subType < 0 )
        {
            Log.e("Account Info Error", "" + accountID + " - " + accountType + " - " + subType);
            finish();
        }

        // Ask for the list of courses
        QueryUtils util = new QueryUtils(this);
        util.sendGetReq( "courseInfo.php", new Callback()
        {
            @Override
            public void onSuccess(String response)
            {
                Log.d("Courses - ", response);

                if( response.equals(" ") )
                {
                    Toast.makeText(getApplicationContext(), "No Courses Found", Toast.LENGTH_LONG).show();
                    return;
                }

                // Build up the ArrayList of courses
                final ArrayList<Section> sectionList = new ArrayList<Section>();

                // Tokenize the response
                StringTokenizer strTok = new StringTokenizer(response, "&&");

                String sectionDate, sectionTime;

                while( strTok.hasMoreTokens() )
                {
                    Section tempSection = new Section();
                    tempSection.setSecCourse( strTok.nextToken() );
                    tempSection.setSecName( strTok.nextToken() );

                    sectionDate = strTok.nextToken();
                    sectionDate = sectionDate.concat(" to "+strTok.nextToken());
                    tempSection.setSecDate( sectionDate );

                    tempSection.setSecDay( strTok.nextToken().substring(0, 3) );

                    sectionTime = strTok.nextToken().substring(0, 5);
                    sectionTime = sectionTime.concat(" - " + strTok.nextToken().substring(0, 5));
                    tempSection.setSecTime(sectionTime);

                    tempSection.setMtrReq("Mentor Req: " + strTok.nextToken() );
                    tempSection.setMntReq("Mentee Req: " + strTok.nextToken() );
                    sectionList.add(tempSection);
                }

                CourseAdapter courseAdapter = new CourseAdapter(getApplicationContext(), sectionList);
                ListView listView = findViewById(R.id.section_list);
                listView.setAdapter(courseAdapter);

                // If clicked on try and add user to course
                listView.setOnItemLongClickListener(new AdapterView.OnItemLongClickListener()
                {
                    @Override
                    public boolean onItemLongClick(AdapterView<?> parent, View view, int position, long id)
                    {
                        // Has to be a student
                        if( accountType == 1)
                        {
                            // Build and show alert
                            buildAlert(accountID, subType, sectionList.get(position).getSecName()).show();
                        }

                        return true;
                    }
                });
            }
        });
    }

    private AlertDialog buildAlert(int userID, int studentType, String sectionName)
    {
        // Query set up
        final Map<String, String> params = new HashMap<String, String>();
        params.put("ID", String.valueOf(userID));
        params.put("sectName", sectionName);
        final QueryUtils alertUtil = new QueryUtils(getApplicationContext());

        AlertDialog.Builder builder = new AlertDialog.Builder(CoursesActivity.this);
        builder.setTitle("Enrollment");

        if( studentType == 0 ) // Mentee only
        {
            builder.setMessage("Enroll in this course as a mentee?");
            builder.setNegativeButton("Cancel", null);
            builder.setPositiveButton("Enroll", new DialogInterface.OnClickListener()
            {
                @Override
                public void onClick(DialogInterface dialog, int which)
                {
                    // Run the query
                    alertUtil.sendQuery(params, "addMentee.php", new Callback()
                    {
                        @Override
                        public void onSuccess(String response)
                        {
                            if( response.equals(" ") ) // Success
                            {
                                response = "Enrolled successfully";
                            }

                            Toast.makeText(getApplicationContext(),response, Toast.LENGTH_LONG ).show();
                        }
                    });
                }
            });
        }
        else if( studentType == 1 ) // Mentor only
        {
            builder.setMessage("Enroll in this course as a mentor?");
            builder.setNegativeButton("Cancel", null);
            builder.setPositiveButton("Enroll", new DialogInterface.OnClickListener()
            {
                @Override
                public void onClick(DialogInterface dialog, int which)
                {
                    // Run the query
                    alertUtil.sendQuery(params, "addMentor.php", new Callback()
                    {
                        @Override
                        public void onSuccess(String response)
                        {
                            if( response.equals(" ") ) // Success
                            {
                                response = "Enrolled successfully";
                            }

                            Toast.makeText(getApplicationContext(),response, Toast.LENGTH_LONG ).show();
                        }
                    });
                }
            });
        }
        else // Could enroll as mentee or mentor
        {
            LayoutInflater inflater = getLayoutInflater();
            final View alertLayout = inflater.inflate(R.layout.enroll_both, null);

            builder.setView(alertLayout);
            builder.setNegativeButton("Cancel", null);
            builder.setPositiveButton("Enroll", new DialogInterface.OnClickListener()
            {
                @Override
                public void onClick(DialogInterface dialog, int which)
                {
                    RadioButton menteeBtn = alertLayout.findViewById(R.id.enroll_alert_mentee);
                    String url = "";

                    if( menteeBtn.isChecked() )
                        url = "addMentee.php";
                    else
                        url = "addMentor.php";

                    // Run the query
                    alertUtil.sendQuery(params, url, new Callback()
                    {
                        @Override
                        public void onSuccess(String response)
                        {
                            if( response.equals(" ") ) // Success
                            {
                                response = "Enrolled successfully";
                            }

                            Toast.makeText(getApplicationContext(),response, Toast.LENGTH_LONG ).show();
                        }
                    });
                }
            });
        }

        return builder.create();
    }

}
