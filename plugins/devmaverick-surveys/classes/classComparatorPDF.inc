<?php
/**
 * Use like this: $dm_comparatorPDF = new DM_ComparatorPDF;
 */
class DM_ComparatorPDF extends DM_Comparator {


  public function college_comparison_block( $college_1 = '', $college_2 = '' ) {

    // $college_1 = 209;
    // $college_2 = 479;

    if ( ! $college_1 ) {
      return false;
    }
    if ( ! $college_2 ) {
      return false;
    }

    if ( $college_1 == $college_2 ) {
      return 'Choose two different colleges, please!';
    }

    $settings = get_option( 'dm_college_comparison_settings' );

    // echo '<pre>';
    // print_r ( $settings );
    // echo '</pre>';

    $college_comparison_data = $this -> calculate_college_comparator_data( $college_1, $college_2, $settings );

    $college_1_image = get_the_post_thumbnail( $college_1, 'Comparator_Thumb' );
    $college_2_image = get_the_post_thumbnail( $college_2, 'Comparator_Thumb' );

    $return = '<table class="dm-pdf-college-comparison-block">
                  <tr class="dm-pdf-college-comparison-block-images">
                      <td colspan="3">
                          ' . $settings['comparator_narrative'] . '
                      </td>
                      <td>
                        <a href="' . get_permalink( $college_1 ) . '">' . $college_1_image . '</a>
                      </td>
                      <td>
                        <a href="' . get_permalink( $college_2 ) . '">' . $college_2_image . '</a>
                      </td>
                  </tr>

                  <tr class="dm-pdf-college-comparison-block-head">
                      <td colspan="1" width="80">

                      </td>
                      <td colspan="2" align="left" width="300">
                        Category
                      </td>
                      <td class="dm-college-name" align="center" width="129">
                        <a href="' . get_permalink( $college_1 ) . '">' . get_the_title( $college_1 ) . '</a>
                      </td>
                      <td class="dm-college-name" align="center" width="128">
                        <a href="' . get_permalink( $college_2 ) . '">' . get_the_title( $college_2 ) . '</a>
                      </td>

                  </tr>
                  ';


    $community_block = '<table>';
    $academics_block = '<table>';
    $social_life_block = '<table>';

    $community_odd = 'odd';
    $academics_odd = 'odd';
    $social_life_odd = 'even';

    $community_count = 0;
    $academics_count = 0;
    $social_life_count = 0;

    foreach ($settings as $key => $question_settings) {
      // If the question ID is not an integer, then it's not selected. Just skip this setting.
      if ( ! is_numeric ($question_settings['question_id']) ) {
        continue;
      }
      // If the response is empty, then there's nothing to search in the database for. Just skip this setting.
      if ( $question_settings['response'] == '' ) {
        continue;
      }
      // Keep track of whether odd or even
      switch ( $question_settings['selected_tab'] ) {
        case 2:
          $community_odd = ( $community_odd == 'odd' ) ? 'even' : 'odd';
          $row_odd_class = $community_odd;
          $community_count ++;
          break;
        case 3:
          $academics_odd = ( $academics_odd == 'odd' ) ? 'even' : 'odd';
          $row_odd_class = $academics_odd;
          $academics_count ++;
          break;
        case 4:
          $social_life_odd = ( $social_life_odd == 'odd' ) ? 'even' : 'odd';
          $row_odd_class = $social_life_odd;
          $social_life_count ++;
          break;

      }

      $current_row = $this -> college_comparison_row( $settings, $college_comparison_data, $key, $college_1, $college_2, $row_odd_class );

      switch ( $question_settings['selected_tab'] ) {
        case 2:
          $community_block .= $current_row;
          break;
        case 3:
          $academics_block .= $current_row;
          break;
        case 4:
          $social_life_block .= $current_row;
          break;

      }

    } // End foreach

    $community_block .= '</table>';
    $academics_block .= '</table>';
    $social_life_block .= '</table>';

    $row_height = 28;

    $community_height   = $community_count * $row_height;
    $academics_height   = $academics_count * $row_height;
    $social_life_height = $social_life_count * $row_height;

    $community_style = ' style="height: ' . $community_height . 'px;line-height: ' . $community_height . 'px;"';
    $academics_style = ' style="height: ' . $academics_height . 'px;line-height: ' . $academics_height . 'px;"';
    $social_life_style = ' style="height: ' . $social_life_height . 'px;line-height: ' . $social_life_height . 'px;"';

    $return .= '<tr class="dm-pdf-main-category">
                  <td class="main-cat" ' . $community_style . '>
                    <span>
                      <i class="fa fa-users" aria-hidden="true"></i>
                      <span>Community</span>
                    </span>
                  </td>
                  <td colspan="4" class="cat-content">' . $community_block . '</td>
                </tr>';
    $return .= '<tr class="dm-pdf-main-category">
                  <td class="main-cat" ' . $academics_style . '>
                    <span>
                      <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                      <span>Academics</span>
                    </span>
                  </td>
                  <td colspan="4" class="cat-content">' . $academics_block . '</td>
                </tr>';
    $return .= '<tr class="dm-pdf-main-category">
                  <td  class="main-cat" ' . $social_life_style . '>
                    <span>
                      <i class="fa fa-weixin" aria-hidden="true"></i>
                      <span>Social life</span>
                    </span>
                  </td>
                  <td colspan="4" class="cat-content">' . $social_life_block . '</td>
                </tr>';

    $return .= '</table>';


    $return .= '<div class="dm-comparator-sub-notes">

          <table width="500">
            <tr>
              <td></td>
            </tr>
            <tr>
              <td width="60">
                  KEY
              </td>
              <td width="600">

                    <table>
                      <tr>
                        <td width="40"><img src="' . SURVEYS_ASSETS_URL  . '/images/big-six.png" width="10" height="10"></td>
                        <td>A "Big 6" Factor based on <a href="' . get_permalink( PAGE_VOICE_OF_STUDENT ) . '">' . get_the_title( PAGE_VOICE_OF_STUDENT ) . '</a></td>
                      </tr>
                      <tr>
                        <td width="40"><img src="' . SURVEYS_ASSETS_URL  . '/images/star-small.png" width="10" height="10"></td>
                        <td>Category Winner by a Significant Margin</td>
                      </tr>
                    </table>

              </td>
            </tr>
          </table>

      </div>';
    return $return;

  }





  public function college_comparison_row( $settings = '', $college_comparison_data = '', $key = '', $college_1 = '', $college_2 = '', $row_odd_class = '' ) {

    // echo '<pre>';
    // print_r ( $college_comparison_data );
    // echo '</pre>';

    foreach ($college_comparison_data as $key_college => $collage_data) {
      if ( ! $college_1_data ) {
        $college_1_data = $collage_data;
        continue;
      }
      if ( ! $college_2_data ) {
        $college_2_data = $collage_data;
      }
    }
    $current_question_id = $settings[$key]['question_id'];

    // echo '$current_question_id: ' . $current_question_id . '<br />';

    $college_1_data = $college_comparison_data[$college_1];
    $college_2_data = $college_comparison_data[$college_2];
    // echo '<pre>';
    // print_r ( $college_comparison_data );
    // echo '</pre>';
    // echo '<pre>';
    // print_r ( $college_1_data );
    // echo '</pre>';
    // echo '<pre>';
    // print_r ( $college_2_data );
    // echo '</pre>';

    $college_1_percentage = round ( ( $college_1_data[$current_question_id]['matching_count'] / $college_1_data[$current_question_id]['total_count'] ) * 100 );
    $college_2_percentage = round ( ( $college_2_data[$current_question_id]['matching_count'] / $college_2_data[$current_question_id]['total_count'] ) * 100 );



    if ( $settings[$key]['reverse_target'] != true ) {
      $difference = $college_1_percentage - $college_2_percentage;
    } else {
      $difference = $college_2_percentage - $college_1_percentage;
    }

    $signifficant_difference = $settings['signifficant_difference'];
    // echo $settings[$key]['category'] . '<br />';
    // echo '$college_1_percentage: ' . $college_1_percentage . '<br />';
    // echo '$college_2_percentage: ' . $college_2_percentage . '<br />';
    //
    // echo '<pre>';
    // print_r ( $settings[$key]['reverse_target'] );
    // echo '</pre>';
    // echo $difference . '<br />';

    $major_difference_star = '<div class="dm-signifficant-difference"><img src="' . SURVEYS_ASSETS_URL  . '/images/star-small.png"></div>';

    if ( $difference >= $signifficant_difference ) {
      $major_difference_star_1 = $major_difference_star;
    }
    if ( $difference <= - $signifficant_difference ) {
      $major_difference_star_2 = $major_difference_star;
    }
    $bix_six_block = '<span class="dm-big-six"><img src="' . SURVEYS_ASSETS_URL  . '/images/big-six.png"></span>';
    $big_six  = ( $settings[$key]['big_six'] == true ) ? '<td width="50">' . $bix_six_block . '</td>' : false;
    $colspan  = ( $big_six ) ? 1 : 2;
    $width    = ( $big_six ) ? 250 : 300;

    if ( $big_six ) {
      $big_six_class = 'dm-has-big-six ';
    }


    $return = '<tr class="dm-comparison-question-row ' . $row_odd_class . '">
                <td width="' . $width . '" colspan="' . $colspan . '">
                  <strong>' . $settings[$key]['category'] . ':</strong>
                  <span>' . $settings[$key]['pretty_response'] . '</span>
                </td>
                ' . $big_six . '
                <td class="dm-score-cell">
                  <table class="dm-college-score-table">
                    <tr>
                      <td class="dm-college-score-table-value">' . $college_1_percentage . '%</td>
                      <td class="dm-college-score-table-star">' . $major_difference_star_1 . '</td>
                    </tr>
                  </table>

                </td>
                <td class="dm-score-cell">
                    <table class="dm-college-score-table">
                      <tr>
                        <td class="dm-college-score-table-value">' . $college_2_percentage . '%</td>
                        <td class="dm-college-score-table-star">' . $major_difference_star_2 . '</td>
                      </tr>
                    </table>
                </td>
              </tr>';
    return $return;

    // echo '<pre>';
    // print_r ( $settings[$key] );
    // echo '</pre>';


  }

  public function style() {
    $return = '<style>
    .dm-pdf-college-comparison-block-head {
      background-color: #47bad0;
      color: #fff;
      font-size: 16px;
      text-align: left;
    }
    .dm-pdf-college-comparison-block-head a {
      color: #fff;
      font-size: 12px;
      text-align: center;
    }
    .dm-pdf-college-comparison-block-head .dm-college-name {
      text-align: center;
        font-size: 14px;
        line-height: 20px;
        padding-top: 25px;
    }
    .dm-pdf-college-comparison-block-head td {
      height: 35px;
      line-height:35px;
    }
    .dm-signifficant-difference img {
      width: 17px;
      height: 15px;
    }

    .dm-big-six {
      padding-top: 10px;
    }
    .dm-big-six img {
      width: 20px;
      height: 20px;
    }

    .odd {
        background-color: #e8f3f7;
    }
    .even {
        background-color: #cfe7ee;
    }

    .dm-pdf-main-category td.main-cat {
      width: 80px;
      border-bottom: 1px solid #d6d6d6;

    }
    .dm-pdf-main-category td.cat-content {
      width: 515px;
      border-bottom: 1px solid #d6d6d6;
    }

    .dm-comparison-question-row td {
      height: 26px;
      line-height: 26px;
    }
    .dm-college-score-table {
      margin-bottom: -10px;
    }

    .dm-college-score-table .dm-college-score-table-value {
      width: 90px;
      text-align: center;
      padding: 0;
    }
    .dm-college-score-table .dm-college-score-table-star {
      width: 30px;

      padding: 0;
    }
    .dm-score-cell {
      border-left: 1px solid #fff;
    }





    	h1 {
    		color: red;
    		font-family: times;
    		font-size: 24pt;
    		text-decoration: underline;
    		width: 50px;
    	}
    	p.first {
    		color: #003300;
    		font-family: helvetica;
    		font-size: 12pt;
    	}
    	p.first span {
    		color: #006600;
    		font-style: italic;
    	}
    	p#second {
    		color: rgb(00,63,127);
    		font-family: times;
    		font-size: 12pt;
    		text-align: justify;
    	}
    	p#second > span {
    		background-color: #FFFFAA;
    	}
    	table.first {
    		color: #003300;
    		font-family: helvetica;
    		font-size: 8pt;
    		border-left: 3px solid red;
    		border-right: 3px solid #FF00FF;
    		border-top: 3px solid green;
    		border-bottom: 3px solid blue;
    		background-color: #ccffcc;
    	}
    	td {

    	}
    	td.second {
    		border: 2px dashed green;
    	}
    	div.test {
    		color: #CC0000;
    		background-color: #FFFF66;
    		font-family: helvetica;
    		font-size: 10pt;
    		border-style: solid solid solid solid;
    		border-width: 2px 2px 2px 2px;
    		border-color: green #FF00FF blue red;
    		text-align: center;
    	}
    	.lowercase {
    		text-transform: lowercase;
    	}
    	.uppercase {
    		text-transform: uppercase;
    	}
    	.capitalize {
    		text-transform: capitalize;
    	}
      img {
        width: 30px;
        height: 30px;
      }
    </style>';

    return $return;
  }



  public function get_pdf_form( $college_1 = '', $college_2 = '') {
      $return = '<div class="dm-download-report-container">
                  <form action="' . SURVEYS_PLUGIN_FOLDER_URL . '/lib/tcpdf/comparator-pdf.php" method="POST">
                    <h2><i class="fa fa-file-pdf-o" aria-hidden="true"></i>  Print This Report</h2>
                    <h4>Enter a Title for Your Printed Report (optional)</h4>

                    <input type="hidden" name="college_1" value="' . $college_1 . '">
                    <input type="hidden" name="college_2" value="' . $college_2 . '">

                    <input type="text" name="custom_title" value="" placeholder=""><br />

                    <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-print" aria-hidden="true"></i></i> Print</button>
                  </form>
                  ';

      return $return;
  }

} // End class
