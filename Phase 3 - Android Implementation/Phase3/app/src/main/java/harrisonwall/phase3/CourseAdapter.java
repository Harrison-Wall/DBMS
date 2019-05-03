package harrisonwall.phase3;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;

public class CourseAdapter extends ArrayAdapter<Section>
{
    public CourseAdapter(Context context, ArrayList<Section> courseList)
    {
        super(context, 0, courseList);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent)
    {
        View courseView = convertView;
        if( courseView == null )
            courseView = LayoutInflater.from(getContext()).inflate(R.layout.course, parent, false);

        // Fill course data
        Section currSection = getItem(position);

        TextView courseName  = courseView.findViewById(R.id.course_name);
        TextView sectionName = courseView.findViewById(R.id.section_name);
        TextView sectionDate = courseView.findViewById(R.id.section_date);
        TextView sectionTime = courseView.findViewById(R.id.section_time);
        TextView mentorReq   = courseView.findViewById(R.id.mentor_req);
        TextView menteeReq   = courseView.findViewById(R.id.mentee_req);
        TextView sectionDay  = courseView.findViewById(R.id.section_week_day);

        courseName.setText(  currSection.getSecCourse() );
        sectionName.setText( currSection.getSecName() );
        sectionDate.setText( currSection.getSecDate() );
        sectionTime.setText( currSection.getSecTime() );
        menteeReq.setText(   currSection.getMntReq() );
        mentorReq.setText(   currSection.getMtrReq() );
        sectionDay.setText(  currSection.getSecDay() );

        return courseView;
    }

}
