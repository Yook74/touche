import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './category.component.html'
})
export class AdminTeamCategoryComponent implements FieldComponent {
    @Input() data: any;
}
