package harrisonwall.phase3;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;

public class SectionEnrollAdapter extends ArrayAdapter<Section>
{
    public SectionEnrollAdapter(Context context, ArrayList<Section> sectionList)
    {
        super(context, 0, sectionList);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent)
    {
        View sectionView = convertView;
        if( sectionView == null )
            sectionView = LayoutInflater.from(getContext()).inflate(R.layout.course, parent, false);

        // Fill course data
        Section currSection = getItem(position);

        TextView sectionName = sectionView.findViewById(R.id.section_name);
        TextView sectionDate = sectionView.findViewById(R.id.section_date);
        TextView sectionTime = sectionView.findViewById(R.id.section_time);
        TextView sectionDay  = sectionView.findViewById(R.id.section_week_day);

        sectionName.setText( currSection.getSecName() );
        sectionDate.setText( currSection.getSecDate() );
        sectionTime.setText( currSection.getSecTime() );
        sectionDay.setText(  currSection.getSecDay() );

        return sectionView;
    }

}
