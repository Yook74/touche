import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './categories.component.html'
})
export class AdminTeamCategoriesComponent implements FieldComponent {
    @Input() data: any;
}
