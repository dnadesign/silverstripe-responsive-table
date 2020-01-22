<div class="responsive__table responsive__table__mobile<% if not DisableMobileAccordion %> accordion<% end_if %>"
  role="presentation">
  <% loop $AccordionTable %>
  <div
    class="responsive__table__column responsive__table__accordion <% if $Top.HideAllColumnHeading %>responsive__table__column--shadow<% end_if %>">
    <% loop $Row %>
    <% if $Heading && not $Top.HideAllColumnHeading %>
    <div role="heading" aria-level="2">
      <button id="responsive__tableAccordion$Up.Pos"
        aria-expanded="<% if $Up.Pos == 1 %>true<% else %>false<% end_if %>"
        class="responsive__table__cell responsive__table__column--name accordion__trigger" type="button"
        aria-controls="accordionContent$Up.Pos">
        <span>$Heading</span>
        <% if not $Top.DisableMobileAccordion %>
        <span class="responsive__table__accordion__button"></span>
        <% end_if %>
      </button>
    </div>
    <div id="accordionContent$Up.Pos" role="region" aria-labelledby="responsive__tableAccordion$Up.Pos"
      class="accordion__panel" <% if $Up.Pos > 1 && not $Top.DisableMobileAccordion %>hidden="" <% end_if %>>
      <% else_if not $Heading  %>
      <div class="responsive__table__cell<% if not $Value %> responsive__table__cell--empty<% end_if %>">
        <% if not $Top.HideAllRowNames %>
        <span class="responsive__table__row--name">$RowName</span>
        <% end_if %>
        <span>$Value</span>
      </div>
      <% end_if %>
      <% end_loop %>
      <% if not $Top.HideAllColumnHeading %></div><% end_if %>
  </div>
  <% end_loop %>
</div>
