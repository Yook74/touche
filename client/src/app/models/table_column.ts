import { Type } from '@angular/core';

export class TableColumn {
    header: string;
    dataField: string;
    displayIsComponent: boolean;
    component: Type<any>;
}