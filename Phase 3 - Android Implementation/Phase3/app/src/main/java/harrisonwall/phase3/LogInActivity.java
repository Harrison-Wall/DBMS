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

public class LogInActivity extends AppCompatActivity
{
    private int accountType;

    private View.OnClickListener logIn = new View.OnClickListener()
    {
        @Override
        public void onClick (View v)
        {
            // check userID from Database
            QueryUtils util = new QueryUtils( getApplicationContext() );
            Map<String, String> params = getInput();

            if( params == null )
                Toast.makeText(getApplicationContext(), "Fill all fields", Toast.LENGTH_LONG).show();
            else {
                // Check for matching account type as well
                params.put("type", String.valueOf(accountType));

                util.sendQuery(params, "sign_in.php", new Callback() {
                @Override
                public void onSuccess(String response)
                {
                    Log.d("LogIn_Success - ", response);

                    // Check if valid response
                    if( response.equals("") )
                    {
                        Toast.makeText(getApplicationContext(), "Error Logging In", Toast.LENGTH_LONG).show();
                        return;
                    }

                    int userID = Integer.parseInt(response);

                    Intent goToUserPage = new Intent(getApplicationContext(), UserPageActivity.class);

                    goToUserPage.putExtra("Type", accountType);
                    goToUserPage.putExtra("ID", userID);

                    startActivity(goToUserPage);
                }
            });}
        }

        private Map<String, String> getInput()
        {
            Map<String, String> retVal = null;

            TextView emailField = (TextView) findViewById(R.id.login_email);
            TextView passField = (TextView) findViewById(R.id.login_password);

            String email = emailField.getText().toString();
            String password = passField.getText().toString();

            if( !email.isEmpty() && !password.isEmpty() )
            {
                retVal = new HashMap<String, String>();

                retVal.put("Email", email);
                retVal.put("Pass", password);
            }

            return retVal;
        }
    };

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);

        // Check bundle for student or parent
        Intent intent = getIntent();
        accountType = intent.getIntExtra("Type", -1);
        switch (accountType)
        {
            case 0:
            case 1:
                Log.d("LogInActivity - ", "Valid account type");
                break;
            default:
                Toast.makeText(this, "Error Log In", Toast.LENGTH_LONG).show();
                finish();
        }

        // Display view
        setContentView(R.layout.log_in);

        // Set up button listener
        Button logInSubmit = (Button) findViewById(R.id.login_submit);
        logInSubmit.setOnClickListener(logIn);

    }
}
