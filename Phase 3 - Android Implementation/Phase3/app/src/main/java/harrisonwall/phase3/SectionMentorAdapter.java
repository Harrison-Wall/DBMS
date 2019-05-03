package harrisonwall.phase3;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.util.ArrayList;

public class SectionMentorAdapter extends ArrayAdapter<Section>
{
    public SectionMentorAdapter(Context context, ArrayList<Section> sections)
    {
        super(context, 0, sections);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent)
    {
        View sectionView = convertView;
        if( sectionView == null )
            sectionView = LayoutInflater.from(getContext()).inflate(R.layout.mentor_section, parent, false);

        // Fill section info
        Section currSec = getItem(position);

        TextView secName = sectionView.findViewById(R.id.mentor_section_name);
        secName.setText( currSec.getSecName() );

        // Add text views for all strings in section info
        ArrayList<String> strings = currSec.getMiscInfo();
        LinearLayout linearLayout = sectionView.findViewById(R.id.mentor_section_info_layout);

        for( int i = 0; i < strings.size(); i++ )
        {
            TextView tempView = new TextView(getContext());
            tempView.setText( strings.get(i) );

            linearLayout.addView(tempView);
        }

        return sectionView;
    }
}
