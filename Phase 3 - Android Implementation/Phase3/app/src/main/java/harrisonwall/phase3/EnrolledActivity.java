package harrisonwall.phase3;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.StringTokenizer;

public class EnrolledActivity extends AppCompatActivity
{
    private int accountID;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.section_list);

        Intent thisIntent = getIntent();
        accountID   = thisIntent.getIntExtra("ID", -1);

        if( accountID == -1 )
        {
            Log.e("Enrolled - ", "Invalid: ID - " + accountID);
            finish();
        }

        QueryUtils utils = new QueryUtils(getApplicationContext());

        Map<String, String> params = new HashMap<String, String>();
        params.put("ID", String.valueOf(accountID));

        //Get and display possible sections to enroll in
        utils.sendQuery(params, "enroll.php", new Callback()
        {
            @Override
            public void onSuccess(String response)
            {
                Log.d("Enrolled - ", response);

                if( response.equals(" ") ) // No sections found
                {
                    Toast.makeText(getApplicationContext(), "You are not enrolled in any sections", Toast.LENGTH_SHORT).show();
                    return;
                }

                // Build and display list of sections
                ArrayList<Section> sectionList = new ArrayList<Section>();

                // Tokenize the response
                StringTokenizer strTok = new StringTokenizer(response, "&&");
                String sectionDate, sectionTime;

                while( strTok.hasMoreTokens() )
                {
                    Section tempSection = new Section();
                    tempSection.setSecName( strTok.nextToken() );

                    sectionDate = strTok.nextToken();
                    sectionDate = sectionDate.concat(" to "+strTok.nextToken());
                    tempSection.setSecDate( sectionDate );

                    tempSection.setSecDay( strTok.nextToken().substring(0, 3) );

                    sectionTime = strTok.nextToken().substring(0, 5);
                    sectionTime = sectionTime.concat(" - " + strTok.nextToken().substring(0, 5));
                    tempSection.setSecTime(sectionTime);

                    sectionList.add(tempSection);
                }

                CourseAdapter courseAdapter = new CourseAdapter(getApplicationContext(), sectionList);
                ListView listView = findViewById(R.id.section_list);
                listView.setAdapter(courseAdapter);

            }
        });
    }

    // Alert builder
}
