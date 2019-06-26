<% if $TableRows && not NoCells %>
<%-- <div class="table__theme--$Theme()"> --%>
<div class="table__responsive">
    <% if not $HideTitle %>
        <h3 class="table__title">$Title</h3>
    <% end_if %>
    <% include ResponsiveTableStandard %>
    <% include ResponsiveTableAccordion %>
    <div class="table__disclaimer">
        <span>$ExtraInfo</span>
    </div>
</div>
<% end_if %>

