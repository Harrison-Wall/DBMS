package harrisonwall.phase3;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import java.util.Map;

public class QueryUtils
{
    private final String BASE_URL = "http://10.0.2.2/code/";

    private RequestQueue reqQue;
    private Context mContext;

    public QueryUtils(Context context)
    {
        mContext = context;
        reqQue = Volley.newRequestQueue(mContext);
    }

    public void sendQuery(final Map<String, String> params, String url, final Callback callback)
    {
        StringRequest request = new StringRequest(Request.Method.POST, BASE_URL+url,
            new Response.Listener<String>()
            {
                @Override
                public void onResponse(String response)
                {
                    callback.onSuccess(response);
                }
            },
            new Response.ErrorListener()
            {
                @Override
                public void onErrorResponse(VolleyError error)
                {
                    Toast.makeText(mContext, "Failed: " + error, Toast.LENGTH_LONG).show();
                }
            }
        )
        {
          @Override
          protected Map<String, String> getParams()
          {
              return params;
          }

        };

        reqQue.add(request);
    }

    public void sendGetReq( String url, final Callback callback )
    {
        StringRequest request = new StringRequest(Request.Method.GET, BASE_URL+url,
                new Response.Listener<String>()
                {
                    @Override
                    public void onResponse(String response)
                    {
                        callback.onSuccess(response);
                    }
                },
                new Response.ErrorListener()
                {
                    @Override
                    public void onErrorResponse(VolleyError error)
                    {
                        Toast.makeText(mContext, "Failed: " + error, Toast.LENGTH_LONG).show();
                    }
                }
        );

        reqQue.add(request);
    }

}
