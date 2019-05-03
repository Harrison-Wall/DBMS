package harrisonwall.phase3;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;

public class SectionModAdapter extends ArrayAdapter<Section>
{
    public SectionModAdapter(Context context, ArrayList<Section> sections)
    {
        super(context, 0, sections);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent)
    {
        View sectionView = convertView;
        if( sectionView == null )
            sectionView = LayoutInflater.from(getContext()).inflate(R.layout.mod_section, parent, false);

        // Fill section info
        Section currSec = getItem(position);

        TextView secName = sectionView.findViewById(R.id.mod_section_name);
        TextView secMod  = sectionView.findViewById(R.id.mod_section_moderator);

        secName.setText( currSec.getSecName() );
        secMod.setText( currSec.getSecModerator() );

        return sectionView;
    }
}
