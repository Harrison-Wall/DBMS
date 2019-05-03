package harrisonwall.phase3;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.util.HashMap;
import java.util.Map;
import java.util.StringTokenizer;

public class UserPageActivity extends AppCompatActivity
{
    // OnClick Listeners
    private View.OnClickListener editListener = new View.OnClickListener() {
        @Override
        public void onClick(View v)
        {
            Intent gotoEditor = new Intent(getApplicationContext(), EditorActivity.class);
            gotoEditor.putExtra("ID", accountID);
            gotoEditor.putExtra("Type", accountType);
            startActivity(gotoEditor);
        }
    };

    private View.OnClickListener courseListener = new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            Intent gotoCourses = new Intent(getApplicationContext(), CoursesActivity.class);
            gotoCourses.putExtra("ID", accountID);
            gotoCourses.putExtra("Type", accountType);
            gotoCourses.putExtra("SubType", studentType);
            startActivity(gotoCourses);
        }
    };

    private View.OnClickListener sectionsListener = new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            Intent gotoEnroll;

            if( accountType == 0) // Parent should see moderated sections
                gotoEnroll = new Intent(getApplicationContext(), ModeratorActivity.class);
            else
                gotoEnroll = new Intent(getApplicationContext(), EnrolledActivity.class);

            gotoEnroll.putExtra("ID", accountID);
            startActivity(gotoEnroll);
        }
    };

    private View.OnClickListener mentorListener = new View.OnClickListener() {
        @Override
        public void onClick(View v)
        {
            Intent gotoMentor = new Intent(getApplicationContext(), MentorActivity.class);
            gotoMentor.putExtra("ID", accountID);
            startActivity(gotoMentor);
        }
    };

    private View.OnClickListener logOutListener = new View.OnClickListener() {
        @Override
        public void onClick(View v)
        {
            // Return to the main activity
            Intent gotoMain = new Intent(getApplicationContext(), MainActivity.class);
            startActivity(gotoMain);
        }
    };


    public int accountType, accountID, studentType;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.user_page);

        // Check intent for account info
        Intent thisIntent = getIntent();
        accountType = thisIntent.getIntExtra("Type", -1);
        switch(accountType)
        {
            case 0: // Parent
            case 1: // Student
                accountID = thisIntent.getIntExtra("ID", -1);
                break;
            default:
                Log.e("onCreate() - ", "Account type is invalid" );
                finish();
                break;
        }

        // Get user Data
        QueryUtils util = new QueryUtils(this);
        Map<String, String> params = new HashMap<String, String >();
        params.put("Type", String.valueOf(accountType));
        params.put("ID", String.valueOf(accountID));

        util.sendQuery( params,"userInfo.php", new Callback()
        {
            @Override
            public void onSuccess(String response)
            {
                Log.d("UserPageActivity - ", response);

                if( response.equals("") )
                {
                    Toast.makeText(getApplicationContext(), "No User info Found", Toast.LENGTH_LONG).show();
                    return;
                }

                // Tokenize the response
                StringTokenizer strTok = new StringTokenizer(response, "&&");
                TextView userName = findViewById(R.id.user_name);
                TextView accountInfo = findViewById(R.id.account_info);

                // Fill in fields and Show button if needed
                if( accountType == 0 ) // Parent
                {
                    userName.setText( strTok.nextToken() );
                    accountInfo.setText( strTok.nextToken() ); // Parent/Moderator
                }
                else // Student
                {
                    userName.setText( strTok.nextToken() );
                    String info;
                    switch (strTok.nextToken()) // Grade
                    {
                        case "1":
                            info = "Freshman";
                            break;
                        case "2":
                            info = "Sophomore";
                            break;
                        case "3":
                            info = "Junior";
                            break;
                        case "4":
                            info = "Senior";
                            break;
                        default:
                            info = "Grade Error";
                            break;
                    }

                    String mentee = strTok.nextToken();
                    info = info.concat(" " + mentee); // Mentee

                    String mentor = strTok.nextToken();     // Mentor

                    if( !mentor.equals(" ") ) // Is a mentor
                    {
                        studentType++;
                        Button mentorButton = findViewById(R.id.goto_mentoring_btn);
                        mentorButton.setVisibility(View.VISIBLE);
                        mentorButton.setOnClickListener( mentorListener );

                        if(!mentee.equals(" ")) // Also a mentee
                            studentType++;
                    }

                    info = info.concat(" " + mentor);
                    accountInfo.setText(info);
                }
            }
        });

        // Set up button listeners
        Button editBtn = (Button) findViewById(R.id.edit_account_btn);
        editBtn.setOnClickListener( editListener );

        Button coursesBtn = (Button) findViewById(R.id.goto_courses_btn);
        coursesBtn.setOnClickListener( courseListener );

        Button sectionsBtn = (Button) findViewById(R.id.goto_sections_btn);
        sectionsBtn.setOnClickListener( sectionsListener );

        Button logoutBtn = (Button) findViewById(R.id.logout_btn);
        logoutBtn.setOnClickListener( logOutListener );
    }

    // Don't let the user go back from this page.
    @Override
    public void onBackPressed() {
        // NOP
    }
}
