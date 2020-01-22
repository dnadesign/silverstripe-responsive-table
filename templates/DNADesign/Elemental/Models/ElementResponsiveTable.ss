<% if $TableRows && not NoCells %>
<%-- <div class="table__theme--$Theme()"> --%>
<div class="responsive__table__wrapper">
  <% if not $HideTitle %>
  <h3 class="responsive__table__title">$Title</h3>
  <% end_if %>
  <% include ResponsiveTableStandard %>
  <% include ResponsiveTableAccordion %>
  <div class="responsive__table__disclaimer">
    <span>$ExtraInfo</span>
  </div>
</div>
<% end_if %>
