import { Component, Input } from '@angular/core';

@Component({
    selector: 'data-table',
    templateUrl: './table.component.html'
})
export class TableComponent {
    @Input() headers: string[];
}