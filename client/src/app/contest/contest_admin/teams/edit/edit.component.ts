import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './edit.component.html'
})
export class AdminTeamEditComponent implements FieldComponent {
    @Input() data: any;
}
