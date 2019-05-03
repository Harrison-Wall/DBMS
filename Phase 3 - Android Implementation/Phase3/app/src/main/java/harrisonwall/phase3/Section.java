package harrisonwall.phase3;

import java.util.ArrayList;

public class Section
{
    private String secCourse, secName, secDate, secTime, mtrReq, mntReq, secDay, secID, secModerator;
    private ArrayList<String> miscInfo;

    public Section()
    {
        secCourse = "";

        secID = "-1";
        secName = "Section Name";
        secDate = "1/1/1970 - 1/1/1970";
        secTime = "00:00:00 - 00:00:00";
        mtrReq = "";
        mntReq = "";
        secDay = "Sunday";
        secModerator = "No Moderator";

        miscInfo = new ArrayList<String>();

    }

    // Setters

    public void setSecCourse(String courseName) { this.secCourse = courseName; }

    public void setSecName(String secName)
    {
        this.secName = secName;
    }

    public void setSecDate(String secDate)
    {
        this.secDate = secDate;
    }

    public void setSecTime(String secTime)
    {
        this.secTime = secTime;
    }

    public void setMtrReq(String mtrReq)
    {
        this.mtrReq = mtrReq;
    }

    public void setMntReq(String mntReq)
    {
        this.mntReq = mntReq;
    }

    public void setSecDay(String secDay) {this.secDay = secDay;}

    public void setSecID(String secID) {this.secID = secID;}

    public void setSecModerator(String secModerator) {this.secModerator = secModerator;}

    public void addInfo(String info) {miscInfo.add(info);}

    // Getters

    public String getSecCourse() {return secCourse;}

    public String getSecName() {return secName;}

    public String getSecDate() {return secDate;}

    public String getSecTime() {return secTime;}

    public String getMntReq() {return mntReq;}

    public String getMtrReq() {return mtrReq;}

    public String getSecDay() {return secDay;}

    public String getSecID() {return secID;}

    public String getSecModerator() {return secModerator;}

    public ArrayList<String> getMiscInfo() {return miscInfo;}
}
