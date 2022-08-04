<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Size;
use App\Models\Priority;
use App\Models\Group;


class RelationTest extends TestCase {

    public function testRelationUserSize() {
        $user = User::where("id", 1)->first();
        $size = Size::whereBelongsTo($user)->get();
        var_dump($user->sizes);
        var_dump($size[0]->name);
        $this->assertTrue(true);
    }

    public function testRelationSizePriority() {
        $size = Size::findOrFail(1);
        $priority = Priority::findOrFail(1);
        var_dump($priority->size->user);
        // var_dump($size->priority);
        $this->assertTrue(true);
    }

    public function testRelationGroupSize() {
        $group = Group::findOrFail(1);
        // var_dump(count($group->sizes));
        $size = Size::findOrFail(1);
        var_dump($size->groups[0]->name_group);
        // var_dump($group->sizes[0]->name);
        $this->assertTrue(true);
    }

}