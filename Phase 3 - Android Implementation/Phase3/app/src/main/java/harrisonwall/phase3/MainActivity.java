package harrisonwall.phase3;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;


public class MainActivity extends AppCompatActivity
{
    private View.OnClickListener logInListener = new View.OnClickListener() {
        @Override
        public void onClick(View v)
        {
            // Build and set intent
            Intent goToLogin = new Intent(getApplicationContext(), LogInActivity.class);

            // Used to set up account page
            if(  v.getId() == R.id.login_student_button )
                goToLogin.putExtra("Type", 1); // Student
            else
                goToLogin.putExtra("Type", 0); // Parent

            startActivity(goToLogin);
        }
    };

    private View.OnClickListener registerListener = new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            Intent goToRegister = new Intent(getApplicationContext(), RegisterActivity.class);

            if( v.getId() == R.id.register_student_button)
            {
                goToRegister.putExtra("Type", 1);
            }
            else
            {
                goToRegister.putExtra("Type", 0);
            }

            startActivity(goToRegister);
        }
    };


    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Log.d("Check Permissions - ", "Already Granted");

        // Set up onClick listeners
        Button studentLogIn = (Button) findViewById(R.id.login_student_button);
        studentLogIn.setOnClickListener(logInListener);

        Button parentLogIn = (Button) findViewById(R.id.login_parent_button);
        parentLogIn.setOnClickListener(logInListener);

        Button studentRegister = (Button) findViewById(R.id.register_student_button);
        studentRegister.setOnClickListener(registerListener);

        Button parentRegister = (Button) findViewById(R.id.register_parent_button);
        parentRegister.setOnClickListener(registerListener);
    }

    // Don't let the user go back from this page.
    @Override
    public void onBackPressed() {
        // NOP
    }
}
