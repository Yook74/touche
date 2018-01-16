import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './delete.component.html'
})
export class AdminTeamDeleteComponent implements FieldComponent {
    @Input() data: any;
}
