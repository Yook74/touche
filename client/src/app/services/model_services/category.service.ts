import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Category } from '../../models/category';

@Injectable()
export class CategoryService {
    constructor(private baseService: BaseService) { }
}