<?php
/**
 * Use like this: $dm_college_actions = new DM_CollegeActions;
 */
class DM_CollegeActions {




  public function button_school_rating( $school_id = '', $user_id = '' ) {

    $dm_note = new DM_Note;

    if ( ! $school_id ) {
      $school_id      = get_the_ID();
    }
    if ( ! $user_id ) {
      $user_id        = get_current_user_ID();
    }

    $my_colleges = new DM_MyColleges;
    $rating = $my_colleges -> get_my_college_rating ( $school_id );

    $return .= '<div class="col-xs-6 col-lg-3 dm-my-rating trigger-school-rating-modal">
        <div class="widget style1 lazur-bg">
            <div class="row vertical-align">
                <div class="col-xs-3">
                    <i class="fa fa-star fa-2x" aria-hidden="true" rating="1"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <h2 class="font-bold">' . $rating . '</h2>
                </div>
            </div>
        </div>
    </div>';

    return $return;

  }

  public function button_school_note() {

    $return .= '<div class="col-xs-6 col-lg-3 dm-my-note trigger-add-note-modal">
        <div class="widget style1 yellow-bg">
            <div class="row vertical-align">
                <div class="col-xs-3">
                    <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <span>Notes</span>
                </div>
            </div>
        </div>
    </div>';

    return $return;

  }

} // End class
