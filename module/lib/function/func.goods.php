<?
    function bookingBirthdateCheck($check_date, $birth_date, $gubun) {
        if (!isDate($check_date) || !isDate($birth_date)) return false;

        if ($gubun == 'adult' || $gubun=='A') {
            $sdate = dateADd("Y", -100, date("Y-m-d"));
            $edate = dateAdd("Y", -12, $check_date);
        } else if ($gubun == 'child' || $gubun=='C') {
            $sdate = dateAdd("d", 1, dateAdd("Y", -12, $check_date));
            $edate = dateAdd("d", -1, dateAdd("Y", 12, $sdate));
        } else if ($gubun == 'infant' || $gubun=='I') {
            $sdate = dateAdd("d", 1, dateAdd("Y", -2, $check_date));
            $edate = dateAdd("d", -1, dateAdd("Y", 2, $sdate));
        } else {
            return false;
        }

        if (dateDiff("d", $sdate, $birth_date) >=0 && dateDiff("d", $edate, $birth_date) <=0) {
            return true;
        } else {
            return false;
        }
    }

    function bookingBirthdatePeroid($check_date, $gubun) {
        if (!isDate($check_date)) return "";

        if ($gubun == 'adult' || $gubun=='A') {
            $sdate = dateADd("Y", -100, date("Y-m-d"));
            $edate = dateAdd("Y", -12, $check_date);
        } else if ($gubun == 'child' || $gubun=='C') {
            $sdate = dateAdd("d", 1, dateAdd("Y", -12, $check_date));
            $edate = dateAdd("d", -1, dateAdd("Y", 10, $sdate));
        } else if ($gubun == 'infant' || $gubun=='I') {
            $sdate = dateAdd("d", 1, dateAdd("Y", -2, $check_date));
            $edate = dateAdd("d", -1, dateAdd("Y", 2, $sdate));
        } else {
            return "";
        }

        return formatDates($sdate, "Y.m.d") ."~". formatDates($edate, "Y.m.d");
    }