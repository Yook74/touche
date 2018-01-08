import { Injectable } from '@angular/core';
import { BaseService } from './base.service';
import { Problem } from '../../models/problem';

@Injectable()
export class ProblemService {
    constructor(private baseService: BaseService) { }
}