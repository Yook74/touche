import { Component } from '@angular/core';

@Component({
    templateUrl: './problems.component.html'
})
export class LiveProblemsComponent {
    headers: string[] = ['Name', 'Attachments', 'Attempts','Submit'];
    constructor() { }
}
