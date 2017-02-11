@if (count($projects))
    <h1 class="title">My projects</h1>
    <ul>
        @foreach ($projects as $project)
            <li>{{ $project->name }}</li>
        @endforeach
    </ul>

    <hr>
@endif
