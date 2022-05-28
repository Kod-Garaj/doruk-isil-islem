@extends('layout') 
@section('content')
<div class="row doruk-content">
    <h4 style="color:#999"><i class="fab fa-wpforms"> </i> SİPARİŞ FORMU</h4>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <template v-if="aktifSiparis === null">
                    <h4 class="card-title">SİPARİŞLER</h4>
                    <button @click="siparisEkle" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> SİPARİŞ EKLE</button>
                    <BR></BR>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
    
                                    <h4 class="card-title">Example</h4>
                                    <p class="card-title-desc">This is an experimental awesome solution for responsive
                                        tables with complex data.</p>
    
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <table id="tech-companies-1" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Company</th>
                                                        <th data-priority="1">Last Trade</th>
                                                        <th data-priority="3">Trade Time</th>
                                                        <th data-priority="1">Change</th>
                                                        <th data-priority="3">Prev Close</th>
                                                        <th data-priority="3">Open</th>
                                                        <th data-priority="6">Bid</th>
                                                        <th data-priority="6">Ask</th>
                                                        <th data-priority="6">1y Target Est</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>GOOG <span class="co-name">Google Inc.</span></th>
                                                        <td>597.74</td>
                                                        <td>12:12PM</td>
                                                        <td>14.81 (2.54%)</td>
                                                        <td>582.93</td>
                                                        <td>597.95</td>
                                                        <td>597.73 x 100</td>
                                                        <td>597.91 x 300</td>
                                                        <td>731.10</td>
                                                    </tr>
                                                    <tr>
                                                        <th>AAPL <span class="co-name">Apple Inc.</span></th>
                                                        <td>378.94</td>
                                                        <td>12:22PM</td>
                                                        <td>5.74 (1.54%)</td>
                                                        <td>373.20</td>
                                                        <td>381.02</td>
                                                        <td>378.92 x 300</td>
                                                        <td>378.99 x 100</td>
                                                        <td>505.94</td>
                                                    </tr>
                                                    <tr>
                                                        <th>AMZN <span class="co-name">Amazon.com Inc.</span></th>
                                                        <td>191.55</td>
                                                        <td>12:23PM</td>
                                                        <td>3.16 (1.68%)</td>
                                                        <td>188.39</td>
                                                        <td>194.99</td>
                                                        <td>191.52 x 300</td>
                                                        <td>191.58 x 100</td>
                                                        <td>240.32</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ORCL <span class="co-name">Oracle Corporation</span></th>
                                                        <td>31.15</td>
                                                        <td>12:44PM</td>
                                                        <td>1.41 (4.72%)</td>
                                                        <td>29.74</td>
                                                        <td>30.67</td>
                                                        <td>31.14 x 6500</td>
                                                        <td>31.15 x 3200</td>
                                                        <td>36.11</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
    
                                    </div>
    
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                </template>
                <template v-else>
                    <h4 class="card-title">SİPARİŞ TAKİP FORMU</h4>
                    <button @click="geri" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left"></i> GERİ</button>
                    <BR></BR>

                    <div class="mb-3 row">
                        <label for="example-date-input" class="col-md-2 col-form-label">Tarih</label>
                        <div class="col-md-10">
                            <input class="form-control" type="date" id="example-date-input">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="sira-no-input" class="col-md-2 col-form-label">Sıra No</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" placeholder="Sıra No" id="sira-no-input">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="example-email-input" class="col-md-2 col-form-label">Müşteri</label>
                        <div class="col-md-10">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select</option>
                                <option>Large select</option>
                                <option>Small select</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <table class="table table-striped table-bordered nowrap" id="urun-detay">
                            <thead>
                                <th>No</th>
                                <th>Malzeme</th>
                                <th>Miktar KG</th>
                                <th>Adet</th>
                                <th>Kalite</th>
                                <th>Yapılacak İşlem</th>
                                <th>İstenilen Sertlik</th>
                                <th>İşlemler</th>
                            </thead>
                            <tbody id="urun-satir-ekle">
                                <tr v-for="(urun, index) in urunler" :key="index">
                                    <td>@{{ index + 1 }}</td>
                                    <td>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Select</option>
                                            <option>Large select</option>
                                            <option>Small select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="Miktar KG" v-model="urun.miktar">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="Adet" v-model="urun.adet">
                                    </td>
                                    <td>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Select</option>
                                            <option>Large select</option>
                                            <option>Small select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Select</option>
                                            <option>Large select</option>
                                            <option>Small select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected>Select</option>
                                            <option>Large select</option>
                                            <option>Small select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" @click="urunSil(index)">Sil</button>
                                    </td>
                                </tr>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <button class="btn btn-success" @click="urunEkle">Ekle</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
@endsection

@section('script')

<script>
    let mixinApp = {
        data: function () {
            return {
                aktifSiparis: null,
                urunler: [],
                urun: {
                    malzeme: '',
                    miktar: '',
                    adet: '',
                    kalite: '',
                    yapilacak_islem: '',
                    istenilen_sertlik: ''
                }
            }
        },
        methods: {
            urunEkle() {
                this.urunler.push(this.urun);
                this.urun = {
                    malzeme: '',
                    miktar: '',
                    adet: '',
                    kalite: '',
                    yapilacak_islem: '',
                    istenilen_sertlik: ''
                }
            },
            urunSil(index) {
                this.urunler.splice(index, 1);
            },
            siparisEkle() {
                this.aktifSiparis = {
                    tarih: '',
                    sira_no: '',
                    musteri: '',
                    urunler: this.urunler
                }
            },
            geri() {
                this.aktifSiparis = null;
            }
        }
    };
</script>
@endsection
