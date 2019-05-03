package harrisonwall.phase3;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.telephony.PhoneNumberUtils;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import java.util.HashMap;
import java.util.Map;

public class RegisterActivity extends AppCompatActivity
{
    private int accountType;

    public View.OnClickListener registerListener = new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            // Get info from shared fields
            QueryUtils util = new QueryUtils( getApplicationContext() );
            Map<String, String> params;
            String url = null;

            switch(accountType)
            {
                case 0: // Parent
                    url = "reg_parent.php";
                    break;
                case 1: // Student
                    url = "reg_student.php";
                    break;
                default:
                    Toast.makeText(getApplicationContext(), "Error RegisterActivity", Toast.LENGTH_LONG).show();
                    return;
            }

            if( ( params = getInput(accountType) ) == null )
            {
                Toast.makeText(getApplicationContext(), "Fill out all fields", Toast.LENGTH_LONG).show();
                return;
            }

            util.sendQuery(params, url, new Callback() {
                @Override
                public void onSuccess(String response)
                {
                    Log.d("Register_Success - ", response);

                    // Check if ID is valid not - 1
                    if( response.equals("") )
                    {
                        Toast.makeText(getApplicationContext(), "Error Registering" + response, Toast.LENGTH_LONG).show();
                        return;
                    }

                    int userID = Integer.parseInt(response);

                    Intent goToUserPage = new Intent(getApplicationContext(), UserPageActivity.class);

                    goToUserPage.putExtra("Type", accountType);
                    goToUserPage.putExtra("ID", userID);

                    startActivity(goToUserPage);
                }
            });
        }

        // Get info
        private Map<String, String> getInput(int accountType)
        {
            Map<String, String> retVal = null;

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

            // Check required fields are filled
            if( !sEmail.isEmpty() && !sPass.isEmpty() && !sName.isEmpty()  )
            {
                if( accountType == 0 )
                {
                    CheckBox mod = (CheckBox) findViewById(R.id.register_moderator_check);

                    retVal = new HashMap<String, String>();
                    // Put Data
                    retVal.put("Email", sEmail );
                    retVal.put("Pass", sPass );
                    retVal.put("Name", sName );
                    retVal.put("City", sCity );
                    retVal.put("State", sState );
                    retVal.put("Phone", sPhone );
                    retVal.put("Mod", String.valueOf( mod.isChecked() ));
                }
                else if( accountType == 1 )
                {
                    CheckBox mentee = (CheckBox) findViewById(R.id.register_mentee_check);
                    CheckBox mentor = (CheckBox) findViewById(R.id.register_mentor_check);
                    TextView parentEmail = (TextView) findViewById(R.id.register_parent_email_edit_text);
                    Spinner  gradeLevel = (Spinner) findViewById(R.id.register_grade_spinner);

                    String sMentee = String.valueOf( mentee.isChecked() );
                    String sMentor = String.valueOf( mentor.isChecked() );
                    String sParentEmail = parentEmail.getText().toString();
                    String sGrade = gradeLevel.getSelectedItem().toString();

                    if( !sParentEmail.isEmpty() )
                    {
                        retVal = new HashMap<String, String>();

                        // Put Data
                        retVal.put("Email", sEmail );
                        retVal.put("Pass", sPass );
                        retVal.put("Name", sName );
                        retVal.put("City", sCity );
                        retVal.put("State", sState );
                        retVal.put("Phone", sPhone );

                        retVal.put("Mentee", sMentee);
                        retVal.put("Mentor", sMentor);
                        retVal.put("ParentEmail", sParentEmail );
                        retVal.put("Grade", sGrade );
                    }
                }
            }

            return retVal;
        }
    } ;

    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.register);

        // Check intent for account info
        Intent thisIntent = getIntent();
        accountType = thisIntent.getIntExtra("Type", -1);

        // show appropriate layouts
        switch(accountType)
        {
            case 0: // Parent
                LinearLayout registerExtraLayout = (LinearLayout) findViewById(R.id.register_parent_extra);
                registerExtraLayout.setVisibility(View.VISIBLE);
                break;
            case 1: // Student
                registerExtraLayout = (LinearLayout) findViewById(R.id.register_student_extras);
                registerExtraLayout.setVisibility(View.VISIBLE);

                // Set up grade spinner
                Spinner gradeSpinner = (Spinner) findViewById(R.id.register_grade_spinner);
                ArrayAdapter<CharSequence> gradeAdapter = ArrayAdapter.createFromResource(this,
                        R.array.grades, android.R.layout.simple_spinner_dropdown_item);
                gradeSpinner.setAdapter(gradeAdapter);
                break;
            default:
                Log.e("onCreate() - ", "Account type is invalid" );
                finish();
                break;
        }

        // set up button listener
        Button registerButton = (Button) findViewById(R.id.register_submit);
        registerButton.setOnClickListener(registerListener);
    }
}
