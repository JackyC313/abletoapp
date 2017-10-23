                <div class="panel-heading">Available Questions</div>
                <div class="panel-body">
                        <div>Questions for you to answer</div>
                        <ul class="list-group">
                        @forelse ($questions_unanswered as $question)
                            <a href="/question/{{ $question->id }}/index" class="list-group-item list-group-item-action">{{ $question->question }}</a>
                        @empty
                            <p class="flow-text center-align">There are no more questions for you to answer</p>
                        @endforelse
                        </ul>
                </div>