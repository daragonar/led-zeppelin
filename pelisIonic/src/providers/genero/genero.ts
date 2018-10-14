import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';

/*
  Generated class for the GeneroProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class GeneroProvider {
  public ApiURL: string = "http://localhost/pelis/servicios/servicioGenero.php";
  //public ApiURL: string = "https://reqres.in/api";
  constructor(public http: HttpClient) {
    //console.log('Hello GeneroProvider Provider');
  }

  getGenres() {
    return new Promise(resolve => {
      this.http.get(this.ApiURL+'/generos').subscribe(data => {
        resolve(data);
      }, err => {
        console.log(err);
      });
    });
  }

}
