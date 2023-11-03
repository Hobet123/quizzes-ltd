@switch($quiz->quiz_sts)
    @case(1)
    <span class="text-danger">Draft</span>
        @break

    @case(2)
    <span class="text-warning">Waiting for approval</span>
        @break
    @case(3)
    <span class=".text-secondary">Disabled</span>
        @break

    @default
    <span class="text-info">Active</span>
@endswitch