jQuery( document ).ready(function($) {
  // Handler for .ready() called.


  //Ajax call to get all the graphs data

  // var iped = $( '.dm-school-tabs' ).attr('school-iped');
  //
  // $.post(
  //         ajaxurl,
  //             {
  //               'action': 'dm_get_charts_data',
  //               'data':   iped
  //             },
  //             function(response){
  //               // console.log(response);
  //
  //               var obj = jQuery.parseJSON( response );
  //
  //               $.each(obj, function( currentQuestionId, optionsObject ) {
  //                 // console.log(currentQuestionId);
  //                 // console.log(optionsObject);
  //
  //                 //Make sure all are arrays.
  //                 var optionsObject = $.map(optionsObject, function(value, index) {
  //                     return [value];
  //                 });
  //
  //                 dm_generate_chart( currentQuestionId, optionsObject );
  //               });
  //
  //             }
  //         );


dmHightlightBestOptions();

function dmHightlightBestOptions() {
    var chartContainers = $('.dm-chart-container');
    chartContainers.each(function( index ) {
      var currentQuestionChartContainer =  $( this );
      var currentChartOptions = currentQuestionChartContainer.find('.dm-option');
      var max = 0;
      currentChartOptions.each(function( index ) {
        var currentMax = parseInt( $( this ).attr('option-percent') );
        if (currentMax > max) {
          max = currentMax;
        }
      });
      currentQuestionChartContainer.find('.dm-option[option-percent="' + max + '"]').addClass('best-response');
    });
}

// drawSatisfactionScoreGraphic();
function drawSatisfactionScoreGraphic() {


  var chart = new Chartist.Pie('.ct-chart', {
  series: [100, 123,  15],
  labels: [1, 3, 2]
}, {
  donut: true,
  showLabel: true
});

chart.on('draw', function(data) {
  if(data.type === 'slice') {
    // Get the total path length in order to use for dash array animation
    var pathLength = data.element._node.getTotalLength();

    // Set a dasharray that matches the path length as prerequisite to animate dashoffset
    data.element.attr({
      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
    });

    // Create animation definition while also assigning an ID to the animation for later sync usage
    var animationDefinition = {
      'stroke-dashoffset': {
        id: 'anim' + data.index,
        dur: 1000,
        from: -pathLength + 'px',
        to:  '0px',
        easing: Chartist.Svg.Easing.easeOutQuint,
        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
        fill: 'freeze'
      }
    };

    // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
    if(data.index !== 0) {
      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
    }

    // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
    data.element.attr({
      'stroke-dashoffset': -pathLength + 'px'
    });

    // We can't use guided mode as the animations need to rely on setting begin manually
    // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
    data.element.animate(animationDefinition, false);
  }
});

}





// Run the flips script on scroll.
$( window ).on('scroll', function () {
  $targetQuestion = $( '.dm-school-tabs' );
    console.log( 'Percentage seen: ' + percentageSeen ( targetQuestion ) );
    if ( percentageSeen ( targetQuestion ) > 10 ) {
        drawSatisfactionScoreGraphic();
    }
});



function percentageSeen ( targetElement ) {
    var viewportHeight = $( window ).height(),
        scrollTop = $( window ).scrollTop(),
        elementOffsetTop = targetElement.offset().top,
        elementHeight = targetElement.height();


    if (elementOffsetTop > (scrollTop + viewportHeight)) {
        return 0;
    } else if ((elementOffsetTop + elementHeight) < scrollTop) {
        return 100;
    } else {
        var distance = (scrollTop + viewportHeight) - elementOffsetTop;
        var percentage = distance / ((viewportHeight + elementHeight) / 100);
        // if ( dmFull == 'full' ) {
        //   return percentage;
        // }
        percentage = Math.round(percentage);
        return percentage;
    }
}


});
