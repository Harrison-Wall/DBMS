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

public class MentorActivity extends AppCompatActivity
{
    private int accountID;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.section_list);

        // Get ID from intent
        Intent thisIntent = getIntent();
        accountID = thisIntent.getIntExtra("ID", -1);

        // If no ID end the activity
        if( accountID == -1 )
            finish();

        final QueryUtils util = new QueryUtils(getApplicationContext());
        final Map<String, String> params = new HashMap<String, String>();
        params.put("ID", String.valueOf(accountID));

        util.sendQuery(params, "mentoring.php", new Callback() {
            @Override
            public void onSuccess(String response)
            {
                Log.d("Mentor - ", response);

                if (response.equals("None")) // No sections being mentored by user
                {
                    Toast.makeText(getApplicationContext(), "You are not mentoring any sections", Toast.LENGTH_LONG).show();
                    return;
                }

                final ArrayList<Section> sections = new ArrayList<Section>();
                StringTokenizer sectTok, infoTok;

                // Tokenize each section
                sectTok = new StringTokenizer(response, "||");

                // Create the array list
                while ( sectTok.hasMoreTokens() )
                {
                    Section tempSect = new Section();

                    // Tokenize the info in each section
                    infoTok = new StringTokenizer(sectTok.nextToken(), "&&");
                    tempSect.setSecName(infoTok.nextToken());

                    // Get all mentee and mentors
                    while( infoTok.hasMoreTokens() )
                    {
                        tempSect.addInfo(infoTok.nextToken());
                    }

                    sections.add(tempSect);
                }

                // Display list
                final SectionMentorAdapter sectionModAdapter = new SectionMentorAdapter(getApplicationContext(), sections);
                ListView listView = findViewById(R.id.section_list);
                listView.setAdapter(sectionModAdapter);
            }
        });
    }
}