import { Component } from '@angular/core';

@Component({
    templateUrl: './problems.component.html'
})
export class JudgeProblemsComponent {
    headers: string[] = ['Name', 'Attachments'];
    constructor() { }
}
