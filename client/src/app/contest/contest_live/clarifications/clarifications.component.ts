import { Component } from '@angular/core';

@Component({
    templateUrl: './clarifications.component.html'
})
export class LiveClarificationsComponent {
    headers: string[] = ['Question', 'Response', 'Time Submitted','Time Answered','Details'];
    constructor() { }
}
