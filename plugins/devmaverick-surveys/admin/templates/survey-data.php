<?php


 ?>

 <div class="wrap">
   <h1>All Survey Data</h1>

   <form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
     <input type="file" name="file">
     <input type="submit" name="btn_submit" value="Upload File" />
   </form>

   <?php
   if ($_FILES) {

     // Read the CSV file and save to a two dimensional array.
     // 0. IPED
     // 1. Question Number
     // 2. Question text
     // 3. School Average

     $fh = fopen($_FILES['file']['tmp_name'], 'r+');
     $lines = array();
     while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
     	$lines[] = $row;
     }

  //    	ini_set('display_startup_errors', 1);
	// ini_set('display_errors', 1);
	// error_reporting(-1);

    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';
    //
    // exit;
     global $wpdb;

        foreach ($lines as $key => $line) {

          if ($key == 0) {

            echo '<pre>';
            print_r($line);
            echo '</pre>';

            echo 'Inserting <br />';

            // Insert the questions to the database table
            $z = 1;
            for($i = 9; $i++;  $i < 78) {
              if ($line[$i] != '') {
                echo $line[$i] . '</br />';
                $result = $wpdb->insert(
                    	'dm_survey_all_questions',
                    	array(
                    		'q_short_text' => $line[$i],
                    		'q_number' => $z,
                        'csv_column' => $i
                    	),
                    	array(
                    		'%s',
                    		'%d',
                        '%d'
                    	)
                    );
                    echo $result . '<br />';
                    $z++;

              }

            }

            break;

          }
          break;

          exit;

          $iped =  $line[0];
          $respondent_id =  $line[1];
          $collector_id =  $line[2];

          $q1_college         = $line[10];  // College
                              // Dropdown
          $q1_college_other   = $line[11];  // College

          $q2         = $line[12]; // How likely is it that you would recommend your school to a friend?
                      // 0 not likely at all
                      // 1
                      // 2
                      // 3
                      // 4
                      // 5 neutral
                      // 6
                      // 7
                      // 8
                      // 9
                      // 10 extremely likely

          $q3         = $line[13]; // What do you like best about your school? (you can have more than 1 answer)
                      // Open Ended

          $q4         = $line[14]; // What would you change about your school? (you can have more than 1 answer)
                      // Open ended

          $q5         = $line[15]; // Overall, how satisfied are you with your college experience and why?
                      // 0 not at all satisfied
                      // 1
                      // 2
                      // 3
                      // 4
                      // 5 neutral
                      // 6
                      // 7
                      // 8
                      // 9
                      // 10 extremely satisfied

          $q5_other   = $line[16]; // Satisfaction Please explain your answer (optional)

          $q6         = $line[17]; // "Sense of community" is defined as a feeling that members have that they belong and they matter to one another and to the group.   How would you rate the sense of community on campus?

                      // Very weak. Students here generally don't feel they belong or they matter, or that their needs will be met by others.
                      // Weak. There is some sense of belonging to a group, but it is not a strong or meaningful attachment.
                      // Average. Students care about being part of the community, but mostly look to themselves or a few close friends to meet their own needs.
                      // Very Strong. Students here feel they belong and matter to the group, and believe that students' needs will be met through their commitment to be together.

          $q7         = $line[18]; // How manageable is the workload at your school?

                      // Easily Manageable - not difficult at all
                      // Very Manageable - occasionally difficult
                      // Manageable - reasonable amount of work
                      // Difficult - it consumes most of my time
                      // Extremely Difficult - it consumes all my time


          $q8_1       = $line[19]; // Across all your classes, how often are you required to... Participate in in-depth group discussions
          $q8_2       = $line[20]; // Across all your classes, how often are you required to... Turn in a Writing Project
          $q8_3       = $line[21]; // Across all your classes, how often are you required to... Give an Oral Presentation
          $q8_4       = $line[22]; // Across all your classes, how often are you required to... Work on a Group Project

                      // Very often (once a week or more)
                      // Somewhat often (at least once a month)
                      // Somewhat seldom (less than once a month but more than once a term)
                      // Very seldom (about once per term)
                      // Never

          $q9         = $line[23]; // A "social clique" is a small group of people who spend time together and who are not friendly to other people. How prevalent are social cliques on campus?

                      // Nonexistent
                      // There are a few groups like this, but not many
                      // They exist, but don't play a big part in campus life
                      // They play a big part at this school
                      // I don’t know
          $q9_other   = $line[24];

          $q10         = $line[25]; // How satisfied are you with your housing?
                      // Not Satisfied
                      // Somewhat Satisfied
                      // Satisfied
                      // Very satisfied
          $q10_other  = $line[26]; // Please explain your answer (optional)

          $q11_1        = $line[27]; // How satisfied are you with the guidance you receive from... Your Advisor?
          $q11_2        = $line[28]; // How satisfied are you with the guidance you receive from... Career Counseling Center?
                      // Not Satisfied
                      // Somewhat Satisfied
                      // Satisfied
                      // Very Satisfied
                      // Not Applicable. Never interacted.

          $q12         = $line[29]; // On average, how much time do you spend studying?
                      // Less than 7 hours per week, or less than an hour a day.
                      // 7-17 hours per week, or about 1-2.5 hours per day.
                      // 18-32 hours per week, or about 2.5-4.5 hours per day.
                      // 33 hours per week or more, or more than 5 hours per day.

          $q13         = $line[30]; // Have you (or do you plan to) completed a significant "capstone" or senior project in your major (i.e. a project that takes more than a single term to complete)?
                      // Yes
                      // No, my major or department offers this option, but I’m not doing it
                      // No, my major or department does not offer this option

          $q14         = $line[31]; // How much difficulty have you had registering for the courses required for your major?
                      // No difficulty. I have always been able to get required courses.
                      // Some difficultly. On more than one occasion, I could not register for a required course.
                      // Extreme difficultly. My graduation date has been pushed out due to not being able to register for several required courses.
                      // Not applicable because I have not yet picked a major.

          $q14_other  = $line[32]; // Other thoughts? (please be specific)

          $q15         = $line[33]; // How safe do you feel on campus, even at night?
                      // Unsafe
                      // Somewhat Safe
                      // Very Safe

          $q16         = $line[34]; // Do most people tend to socialize on campus or off campus?
                      // Mostly on-campus
                      // Slightly more on-campus
                      // Equal balance between on and off-campus
                      // Slightly more off-campus
                      // Mostly off-campus

          $q17         = $line[35]; // How would you describe your relationships with your professors (not including other instructors)?
                      // Very distant. I don’t know my professors and they don’t know me.
                      // Distant. We acknowledge each other on campus but that’s it.
                      // Somewhat close. I’m pretty comfortable going to office hours or chatting with professors after class.
                      // Close. I’m very comfortable with my professors. They know me and I know them. I am comfortable asking for advice and feel I
                      // have many professors I can turn to for guidance.

          $q18         = $line[36]; // Have you ever felt discriminated against based on social or physical characteristics? (e.g. gender, ethnicity, family background, sexual orientation, religion, country of origin).
                      // Never.
                      // A couple of times, but nothing major.
                      // Yes, sometimes. It happens more than I wish it did.
                      // Yes, often. It happens all the time.

          $q18_other   = $line[37];  // Other thoughts? (please be specific)

          $q19         = $line[38]; // How involved are students at your school in the local community?
                      // Not at all. There’s like an invisible wall around campus.
                      // A little. Some people work, have internships, or volunteer off campus.
                      // Quite a bit. A lot of people work, have internships, and/or volunteer in the community.
                      // Very involved. I know a lot of people who work, have internships, and/or volunteer in the community.

          $q20         = $line[39]; // To what extent do you agree with the following:  I have at least one professor who makes me excited about learning.
                      // Strongly Disagree
                      // Disagree
                      // Agree
                      // Strongly Agree

          $q21         = $line[40]; // In your experience, how important is alcohol to the social life at your college?
                      // Vital, if you don't drink then you will stand out.
                      // Somewhat Important, drinking is part of most activities.
                      // Somewhat Unimportant, alcohol doesn't play much of a role at this school.
                      // Insignificant, hardly anyone drinks at social events.
          $q21_other  = $line[41]; // Other thoughts? (please be specific)

          $q22         = $line[42]; // Is the academic environment at your school...
                      // Highly Competitive. Most students put their own success above others, and often compare their achievements to their peers in a
                      // competitive way.
                      // Competitive. Students work hard and have a competitive mentality, but can still work together when necessary.
                      // Collaborative. Overall, students work together well and don’t see school as a competition.
                      // Highly Collaborative. Working together to come up with the best solution is the norm. This is more important that trying to stand
                      // out individually.
          $q22_other  = $line[43]; // Other thoughts? (please be specific)

          $q23         = $line[44]; // In your experience, how prevelant are illegal drugs on campus? (not including marijuana)
                      // Nonexistent. I never see these.
                      // Somewhat around. It’s there but not a lot of it.
                      // Important. One or more of these drugs are a part of most activities.
                      // Vital. If you don’t partake you won’t fit in.
          $q23_other  = $line[45]; // Which drug(s) is most prevelant? (optional)

          $q24         = $line[46]; // Have you been surprised by any changes to your financial aid package?
                      // Not Surprised. I expected the changes I saw.
                      // Somewhat surprised. There were more changes than I expected.
                      // Very surprised. My package changed significantly and I didn't expect that.
                      // Did not see any changes to my package.
                      // I don’t receive financial aid.

          $q25         = $line[47]; // In general, “liberal” people in the US tend to promote social justice and equality, and ask the government to help make society more fair. “Conservative” people tend to promote traditional social values and prefer less government involvement. In your opinion, is your school more liberal or more conservative?
                      // Very liberal
                      // Liberal
                      // Neutral. Neither liberal nor conservative.
                      // Conservative
                      // Very conservative
                      // I'm not sure

          $q26         = $line[48]; // How active are you in extracurricular activities and organizations at your school?
                      // Not active. I don’t participate in these programs.
                      // Somewhat active. I am involved monthly in one activity or organization.
                      // Pretty active. I am involved weekly in one or a combination of activities and organizations.
                      // Extremely active. I am involved daily or almost daily in activities and organizations.

          $q27        = $line[49]; // What is the climate of political activism at your school?
                      // Uninvolved/nonexistent
                      // Somewhat existent. Every once in a while you see students protesting something or passing out political leaflets.
                      // Visible. Some students are politically involved, and may protest sometimes, but the protests are usually small and short-lived.
                      // Prominent. You can’t be here without being involved in the world’s politics on this campus in one way or another.

          $q28         = $line[50]; // What is the availability of on-campus academic assistance resources (where you have a tutor or leader), e.g. writing centers and led study groups?
                      // Nonexistent
                      // Some. They have some, but only for certain classes and majors
                      // Pretty good. You can usually get help if you need it.
                      // Great. You can always get the kind of help you need.

          $q29         = $line[51]; // Across all your courses, what percentage of your classes are led by Teaching Assistants (TAs)?
                      // None of my classes are taught by TAs.
                      // Some of my classes are taught by TAs.
                      // Many of my classes are taught by TAs.
                      // Most of my classes are taught by TAs.

          $q30         = $line[52]; // Do you feel that your professors’ political views affect their teaching?
                      // No. I don’t even know what my professors’ political views are.
                      // Somewhat. Their political viewpoints are known but mostly kept separate from their teaching.
                      // Significantly. Their political viewpoints are integrated into most of their teaching.

          $q31         = $line[53]; // What is your school’s norm toward sexual behavior in general?
                      // Abstinence is the promoted option.
                      // Discreet. Most encounters kept quiet and/or most couples are monogamous.
                      // Average. There are plenty of hookups but it’s usually within the norms of greater society.
                      // Sexual activity is everywhere and people are open about it.

          $q32_1         = $line[54]; // What is the availability of support services for: Women
          $q32_2         = $line[55]; // What is the availability of support services for: Diversity
          $q32_3         = $line[56]; // What is the availability of support services for: LGBTQ
          $q32_4         = $line[57]; // What is the availability of support services for: Disabilities
          $q32_5         = $line[58]; // What is the availability of support services for: Mental Health
                      // We have support services
                      // We don't have support services
                      // I don't know

          $q33         = $line[59]; // What sort of voice do students have in school decision making (i.e. through student government)?
                      // None.
                      // Some. We have student government, but I don’t think they can do much.
                      // Great. We have student government and they work well with the administration to meet the students’ needs.
                      // I don’t really know.

          $q34         = $line[60]; // What is your school’s climate concerning homosexual behavior?
                      // It is forbidden at this school.
                      // It is frowned upon here.
                      // It is tolerated here.
                      // This school is gay-friendly.

          $q35         = $line[61]; // In general, do you feel your campus supports President Obama and the work he is trying to do?
                      // Yes. Strong support for Obama.
                      // Yes. Moderate support for Obama.
                      // Neutral. A balance of support an opposition.
                      // No, Moderate opposition to Obama.
                      // No. Strong opposition to Obama.

          $q36         = $line[62]; // What graduating class are you a part of?
                      // 2015
                      // 2016
                      // 2017
                      // 2018
                      // 2019

          $q37         = $line[63]; // Gender
                      // Male
                      // Female
                      // Other

          $q38         = $line[64]; // What is your ethnicity? (Please select all that apply.)
                      // American Indian or Alaskan Native
                      // Asian or Pacific Islander
                      // Black or African American
                      // Hispanic or Latino
                      // White / Caucasian
                      // Prefer not to answer

                      // CHECKBOXES


          $q39        = $line[72]; // What is your DECLARED major? (mark undeclared if you have not yet picked your major)
                      // DROPDOWN

          $q40         = $line[73]; // What is your employment status?
                      // Employed full time off campus
                      // Employed part-time, off campus
                      // Employed part-time, on campus
                      // Not employed

          $q41         = $line[74]; // Are you an international student?
                      // Yes
                      // No

          $q42         = $line[75]; // Are you a member of the Greek system at your school (i.e. are you in a sorority or fraternity?)
                      // No
                      // Yes
                      // Not applicable. We don't have a Greek system here.

          $q43         = $line[76]; // Where do you live?
                      // On-campus housing
                      // Greek community
                      // Off-campus housing, nearby
                      // Off campus. I commute.

          $q44         = $line[77]; // Do you consider yourself to be:
                      // Heterosexual or straight
                      // Gay or lesbian
                      // Bisexual
                      // Transgender
                      // Questioning or unsure
                      // I prefer not to respond

          $q45         = $line[78]; // Are you an NCAA student athlete?
                      // No - I am not an NCAA athlete
                      // Yes - with an athletic scholarship
                      // Yes - without an athletic scholarship

          // $q46_name   = $line[76]; // Your Contact Info (optional)
          // $q46_email  = $line[84]; // Your Contact Info (optional)

        }

     exit;

     global $wpdb;

     foreach ($lines as $key => $line) {
       if ($key == 0) continue;

       $iped        = $line[0];
       $q_number    = $line[1];
       $q_text      = $line[2];
       $school_ave  = $line[3];

      //  echo 'Key = ' . $key . '<br />';

      // 1. First try to update the existing values.
      // If there is none, the function will return false and we'll then insert the new value.


      $sql_count = ' SELECT COUNT(*) as count FROM dm_school_averages
                      WHERE iped = ' . $iped . '
                        AND q_number = ' . $q_number;


      $count =      $wpdb->get_row( $sql_count );
      $count = $count -> count;

      // $mylink = $wpdb->get_row( $sql_count );

                        // echo $sql_count . '<br /><br />';
      //
      // echo '<pre>';
      // print_r($count);
      // echo '</pre>';


      $sql_update = ' UPDATE dm_school_averages
                      SET school_ave = ' . $school_ave . '
                      WHERE iped = ' . $iped . '
                        AND q_number = ' . $q_number;

      $sql_insert = 'INSERT
                    INTO dm_school_averages (iped, q_number, school_ave)
                    VALUES (' . $iped . ', ' . $q_number . ', ' . $school_ave . ')';

      if ($count > 0) { // Update
        // echo $sql_update . '<br />';
        $result = $wpdb->query( $sql_update );
        $message_ok     = 'Successfully updated the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
        $message_false  = 'There was an error updating the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
        $message_nochange  = 'There was no change for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
      } else { // Insert
        // echo $sql_update . '<br />';
        $result = $wpdb->query( $sql_insert );
        $message_ok     = 'Successfully inserted the record for IPED ' . $iped . ' and Question #' . $q_number . '<br />';
      }

      if (false === $result) {
        echo $message_false . '<br />';
      } elseif ($result == 0) {  // If the return was 0, not FALSE, display that there was no change.
        echo $message_nochange . '<br />';
      } else {
        echo $message_ok . '<br />';
      }

      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';

    }

    //  echo '<pre>';
    //  print_r($lines);
    //  echo '</pre>';

   }
   ?>


   <form method="post" action="options.php">



   </form>
</div>
