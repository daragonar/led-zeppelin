import { Component } from '@angular/core';
import { NavController, NavParams } from 'ionic-angular';
import { GeneroProvider } from "../../providers/genero/genero";

@Component({
  selector: 'page-list',
  templateUrl: 'list.html'
})
export class ListPage {
  selectedItem: any;
  public generos: any;

  constructor(public navCtrl: NavController, public navParams: NavParams, private genero:GeneroProvider) {
     // If we navigated to this page, we will have an item available as a nav param
    this.selectedItem = navParams.get('item');
    this.generos=[]
  }

  ionViewDidLoad() {
    this.getGeneros()
  }

  getGeneros(){
    this.genero.getGenres().then(data=>{
    let gene:any=data;
     this.generos=gene.Generos;
    })
  }

  itemTapped(event, item) {
    // That's right, we're pushing to ourselves!
    this.navCtrl.push(ListPage, {
      item: item
    });
  }
}
