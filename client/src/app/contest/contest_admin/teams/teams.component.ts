import { Component } from '@angular/core';

@Component({
    templateUrl: './teams.component.html'
})
export class AdminTeamsComponent {
    headers: string[] = ['Name', 'Organization', 'Coach','Site','Categories','Edit','Delete'];
    constructor() { }
}
