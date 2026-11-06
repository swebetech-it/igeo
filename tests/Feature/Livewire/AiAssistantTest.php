<?php

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        Livewire::test('ai-assistant')
            ->assertStatus(200);
    }
}
