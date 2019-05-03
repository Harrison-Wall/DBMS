package harrisonwall.phase3;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.StringTokenizer;

public class ModeratorActivity extends AppCompatActivity
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

        util.sendQuery(params, "moderate.php", new Callback() {
            @Override
            public void onSuccess(String response)
            {
                Log.d("Moderate - ", response);

                if( response.equals("Not a Mod") ) // Not a moderator
                {
                    Toast.makeText(getApplicationContext(), "You are not a moderator", Toast.LENGTH_LONG).show();
                    return;
                }

                final ArrayList<Section> sections = new ArrayList<Section>();
                StringTokenizer strTok = new StringTokenizer(response, "&&");

                // Create the array list
                while(strTok.hasMoreTokens())
                {
                    Section tempSect = new Section();

                    tempSect.setSecID( strTok.nextToken() );
                    tempSect.setSecName( strTok.nextToken() );
                    tempSect.setSecModerator( strTok.nextToken() );

                    sections.add(tempSect);
                }

                // Display list
                final SectionModAdapter sectionModAdapter = new SectionModAdapter(getApplicationContext(), sections);
                ListView listView = findViewById(R.id.section_list);

                listView.setAdapter(sectionModAdapter);

                listView.setOnItemLongClickListener(new AdapterView.OnItemLongClickListener()
                {
                    @Override
                    public boolean onItemLongClick(AdapterView<?> parent, View view, int position, long id)
                    {
                        // if that item does not have a moderator
                        if( sections.get(position).getSecModerator().equals("None") )
                        {
                            // Ask if they want to moderate
                            buildDialog(util, params, sections.get(position).getSecID()).show();
                        }

                        return true;
                    }
                });
            }
        });
    }

    private AlertDialog buildDialog(final QueryUtils util, final Map<String, String> args, final String secID)
    {
        // Show Alert
        AlertDialog.Builder builder = new AlertDialog.Builder(ModeratorActivity.this);

        builder.setTitle("Enroll");
        builder.setMessage("Moderate this Section?");
        builder.setNegativeButton("No", new DialogInterface.OnClickListener()
        {
            @Override
            public void onClick(DialogInterface dialog, int which)
            {
                dialog.cancel();
            }
        });
        builder.setPositiveButton("Yes", new DialogInterface.OnClickListener()
        {
            @Override
            public void onClick(DialogInterface dialog, int which)
            {
                args.put("secID", secID );

                // Try and add the user as a mod
                util.sendQuery(args, "addMod.php", new Callback()
                {
                    @Override
                    public void onSuccess(String response)
                    {
                        Log.d("Add Mod - ", response);

                        if( !response.equals(" ") ) // If successful, no response
                        {
                            Toast.makeText(getApplicationContext(), "Could not Add",
                                    Toast.LENGTH_SHORT).show();
                            return;
                        }

                        recreate(); // Remake the list with updated info
                    }
                });
            }
        });

        return builder.create();
    }

}
