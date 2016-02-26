describe 'boolParse unit tests', ->
  $filter = null

  beforeEach ->
    module('astroApp')
    inject (_$filter_)->
      $filter = _$filter_

  it 'should return true when the value is a true value', ->
    expect($filter('boolparse')('1')).toEqual true
    expect($filter('boolparse')(1)).toEqual true
    expect($filter('boolparse')('true')).toEqual true
    expect($filter('boolparse')(true)).toEqual true

  it 'should return false when the value is a false value', ->
    expect($filter('boolparse')('0')).toEqual false
    expect($filter('boolparse')(0)).toEqual false
    expect($filter('boolparse')('false')).toEqual false
    expect($filter('boolparse')(false)).toEqual false
    expect($filter('boolparse')('pantera')).toEqual false
    expect($filter('boolparse')(24)).toEqual false