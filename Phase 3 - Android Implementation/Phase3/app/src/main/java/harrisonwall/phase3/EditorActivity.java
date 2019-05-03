package harrisonwall.phase3;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.telephony.PhoneNumberUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import java.util.HashMap;
import java.util.Map;

public class EditorActivity extends AppCompatActivity
{
    private int accountID, accountType;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.register);

        Intent thisIntent = getIntent();
        accountID   = thisIntent.getIntExtra("ID", -1);
        accountType = thisIntent.getIntExtra("Type", -1);

        if( accountID < 0 || accountType < 0 ) // bad input
        {
            Log.e("Editor - ", "Info " + accountID + " - " + accountType);
            finish();
        }

        // set up button listener
        Button submitBtn = (Button) findViewById(R.id.register_submit);
        submitBtn.setOnClickListener(submitListener);

    }

    public View.OnClickListener submitListener = new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            // Get info from shared fields
            QueryUtils util = new QueryUtils( getApplicationContext() );
            Map<String, String> params;

            params = getInput();
            params.put("ID", String.valueOf(accountID));

            util.sendQuery(params, "edit_user.php", new Callback() {
                @Override
                public void onSuccess(String response)
                {
                    Log.d("Editor - ", response);

                    if( !response.equals(" ") )
                    {
                        Toast.makeText(getApplicationContext(), "Error Updating: " + response, Toast.LENGTH_LONG).show();
                        return;
                    }

                    Intent goToUserPage = new Intent(getApplicationContext(), UserPageActivity.class);

                    goToUserPage.putExtra("Type", accountType);
                    goToUserPage.putExtra("ID", accountID);

                    startActivity(goToUserPage);
                }
            });
        }
    };

    // Get info
    private Map<String, String> getInput()
    {
        Map<String, String> retVal = new HashMap<String, String>();;

        TextView email = (TextView) findViewById(R.id.register_email_edit_text);
        TextView password = (TextView) findViewById(R.id.register_password_edit_text);
        TextView name = (TextView) findViewById(R.id.register_name_edit_text);

        TextView city = (TextView) findViewById(R.id.register_city_edit_text);
        TextView state = (TextView) findViewById(R.id.register_state_edit_text);
        TextView phone = (TextView) findViewById(R.id.register_phone_edit_text);

        String sEmail = email.getText().toString();
        String sPass = password.getText().toString();
        String sName = name.getText().toString();
        String sCity = city.getText().toString();
        String sState = state.getText().toString();
        String sPhone = phone.getText().toString();

        if(!sPhone.isEmpty())
            PhoneNumberUtils.formatNumber(sPhone);

        // Put Data
        retVal.put("Email", sEmail );
        retVal.put("Pass", sPass );
        retVal.put("Name", sName );
        retVal.put("City", sCity );
        retVal.put("State", sState );
        retVal.put("Phone", sPhone );

        return retVal;
    }

}
