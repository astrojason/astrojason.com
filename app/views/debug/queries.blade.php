@foreach($queries as $query)
  <p>
    <% $query['query'] %><br />
    <% implode(', ', $query['bindings']) %>
  </p>
@endforeach