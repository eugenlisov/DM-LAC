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










    // $( ".dm-question-block" ).each(function( index ) {
    //   var currentQuestionId = $( this ).attr('question-id');
    //
    //   // console.log(currentQuestionId);
    //   // console.log( index + ": " + $( this ).text() );
    //   // dm_generate_chart( currentQuestionId );
    // });


    // The chart
    function dm_generate_chart( currentQuestionId, optionsObject ) {











      var questionClass = '.dm-question-' + currentQuestionId;

      console.log(currentQuestionId);
      // console.log( 'Option object' );
      console.log(optionsObject);


      data = [];

      $( optionsObject ).each(function( index, value ) {
        // console.log( index );
        // console.log( 'Value is this: ' );
        // console.log( value );
        // var record = '';
        record = {label: value.option_text, value:value.percent, nelu:"gigi"};
        // console.log( ' displaying the option text');
        // console.log( value.option_text );
        // console.log( value );
        // console.log( '---------' );
        // console.log( record );
        data.push(record);

      });

      // data = [
      // {label:"Option One", value:9},
      // {label:"Option  2", value:5},
      // {label:"Option  3", value:13},
      // {label:"Option  4", value:17},
      // {label:"Option  5", value:10},
      // {label:"Option  6", value:27}
      // ];
      // data = 'test';
      // console.log(data);




      var div = d3.select( questionClass ).append("div").attr("class", "toolTip");

      var axisMargin = 20,
      margin = 40,
      valueMargin = 4,
      // width = parseInt(d3.select('body').style('width'), 10),
      width = 650,
      // height = parseInt(d3.select('body .content').style('height'), 10),
      height = 350,
      height  = data.length * 50,
      barHeight = (height-axisMargin-margin*2)* 0.4/data.length,
      barHeight = 20,
      barPadding = (height-axisMargin-margin*2)*0.6/data.length,
      data, bar, svg, scale, xAxis, labelWidth = 0;

      max = d3.max(data, function(d) { return d.value; });

      svg = d3.select( questionClass )
      .append("svg")
      .attr("width", width)
      .attr("height", height);


      bar = svg.selectAll("g")
      .data(data)
      .enter()
      .append("g");

      bar.attr("class", "bar")
      .attr("cx",0)
      .attr("transform", function(d, i) {
      		return "translate(" + margin + "," + (i * (barHeight + barPadding) + barPadding) + ")";
      });

      bar.append("text")
      .attr("class", "label")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em") //vertical align middle
      .text(function(d){
      		return d.label;
      }).each(function() {
      labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
      });

      scale = d3.scale.linear()
      .domain([0, max])
      .range([0, width - margin*2 - labelWidth]);

      xAxis = d3.svg.axis()
      .scale(scale)
      .tickSize(-height + 2*margin + axisMargin)
      .orient("bottom");

      bar.append("rect")
      .attr("transform", "translate("+labelWidth+", 0)")
      .attr("height", barHeight)
      .attr("width", function(d){
      		return scale(d.value);
      });

      bar.append("text")
      .attr("class", "value")
      .attr("y", barHeight / 2)
      .attr("dx", -valueMargin + labelWidth) //margin right
      .attr("dy", ".35em") //vertical align middle
      .attr("text-anchor", "end")
      .text(function(d){
      		return (d.value+"%");
      })
      .attr("x", function(d){
      		var width = this.getBBox().width;
      		return Math.max(width + valueMargin, scale(d.value));
      });

      bar
      .on("mousemove", function(d){
      		div.style("left", d3.event.pageX+10+"px");
      		div.style("top", d3.event.pageY-25+"px");
      		div.style("display", "inline-block");
      		div.html((d.label)+"<br>"+(d.value)+"%");
      });
      bar
      .on("mouseout", function(d){
      		div.style("display", "none");
      });

      svg.insert("g",":first-child")
      .attr("class", "axisHorizontal")
      .attr("transform", "translate(" + (margin + labelWidth) + ","+ (height - axisMargin - margin)+")")
      .call(xAxis);

    }

});
