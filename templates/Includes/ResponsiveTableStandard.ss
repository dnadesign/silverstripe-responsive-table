<div class="table">
    <% loop $StandardTable %>
        <% if $Pos = 1 && not $Top.HideAllColumnHeading || $Pos > 1 %>
            <div class="table__row <% if $Pos = 1 %>table__headings<% end_if %>">
                <% loop $Row %>
                    <% if $Pos = 1 && not $Top.HideAllRowNames || $Pos > 1 %>
                        <div class="table__cell<% if not $Value %> table__cell--empty<% end_if %><% if $Pos = 1 && not $Top.HideAllRowNames %> table__row--name<% end_if %><% if not $Top.HideAllColumnHeading && $Pos > 1 && $Up.Pos == 1 %> table__column--name<% end_if %>">
                            <span>$Value</span>
                        </div>
                    <% else %>
                        <% if $Top.HideAllRowNames %><div></div><% end_if %>
                    <% end_if %>
                <% end_loop %>
            </div>
        <% end_if %>
    <% end_loop %>
</div>
