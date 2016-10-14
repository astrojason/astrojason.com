angular.module('astroApp').controller 'ChartController', [
  '$scope'
  '$log'
  'LinkResource'
  ($scope, $log, LinkResource)->
    report_promise = LinkResource.report().$promise

    report_promise.then (response)->
      svg = dimple.newSvg '#chart_container', 1020, 600
      myChart = new dimple.chart svg, response.report
      myChart.setBounds 60, 30, 960, 510
      myChart.addLegend 65, 10, 960, 20, "right"
      x = myChart.addCategoryAxis "x", 'Date'
      x.addOrderRule 'Date'
      y = myChart.addMeasureAxis "y", 'Articles'
      myChart.addSeries 'Action', dimple.plot.line
      myChart.draw()
]