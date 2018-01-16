import { Component } from '@angular/core';

@Component({
    templateUrl: './submissions.component.html'
})
export class JudgeSubmissionsComponent {
    headers: string[] = ['Team', 'Problem', 'Attempt','Response','Submitted','Judge'];
    constructor() { }
}
