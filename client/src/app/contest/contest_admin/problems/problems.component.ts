import { Component } from '@angular/core';

@Component({
    templateUrl: './problems.component.html'
})
export class AdminProblemsComponent {
    headers: string[] = ['Name', 'Location', 'Attachments','Data Sets','Edit','Delete'];
    constructor() { }
}
