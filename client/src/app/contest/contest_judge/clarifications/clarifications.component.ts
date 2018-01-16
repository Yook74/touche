import { Component } from '@angular/core';

@Component({
    templateUrl: './clarifications.component.html'
})
export class JudgeClarificationsComponent {
    headers: string[] = ['Problem', 'Question', 'Response','Time Submitted','Time Answered','Answer'];
    constructor() { }
}
