<div class="footer">
	<table>
		<tr>
			<td>{{ $project->city->name or '' }}, {{ $project->name or '' }}</td>
		</tr>
		<tr>
			<td>{{ ucwords($project->address) }}</td>
		</tr>
	</table>
</div>