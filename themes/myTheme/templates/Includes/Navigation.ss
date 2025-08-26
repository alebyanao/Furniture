<nav class="primary">
	<button class="nav-open-button">²</button>
	<ul>
		<% loop $Menu(1) %>
		<% if $URLSegment != 'cart' %>
			<li class="nav-item">
			<a class="nav-link $LinkingMode" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
			</li>
		<% end_if %>
		<% end_loop %>
	</ul>
</nav>