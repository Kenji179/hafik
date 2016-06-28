<?php
$title = 'Rezervaceace | Hafík - hlídání dětí v Kutné Hoře';
$menu = '2'; // 0 or an empty string for nothing highlighted

include('inc_header.php');
//require_once 'db_queries.php';
require_once 'db_queries.php';
//include 'helpers.php';

//require_once 'vendor/autoload.php';
//use mikehaertl\wkhtmlto\Pdf;
//
//echo exec('whoami');
//$pdf = new Pdf('email.html');
//$pdf->binary = '/usr/local/bin/wkhtmltopdf';
//if (!$pdf->saveAs('email.pdf')) {
//    echo $pdf->getError();
//}
//exit;
?>


    <!-- Breadcrumb -->

    <section class="sub-nav">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="index.php">Úvod</a></li>
                    <li class="active">Rezervace</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Breadcrumb End -->

    <!-- Content -->


    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php
                    displayFlash('registration');
                    displayFlash('registration-form-error');

                    if (array_key_exists('reg-form-data', $_SESSION)) {
                        $oldFormData = $_SESSION['reg-form-data'];
                    } else {
                        $oldFormData = [];
                        $oldFormData['care'] = [];
                        $oldFormData['care'][1] = [];
                        $oldFormData['care'][2] = [];
                        $oldFormData['care'][3] = [];
                        $oldFormData['care'][4] = [];
                        $oldFormData['care'][5] = [];
                        $oldFormData['otherGuardians'] = [];
                        $oldFormData['otherGuardians'][1] = [];
                        $oldFormData['otherGuardians'][2] = [];
                    }
                ?>

                <h1>Rezervace</h1>
<!--                <p class="bg-danger">Rezervace se právě testují online - prosím nepoužívejte je. Online rezervace bude dostupná od 30. 4. 2016. Děkujeme</p>-->
                <p>Rezervace online je volitelná. Do dětského centra můžete přivést Vaše dítě i bez předchozího objednání, s námi vyplnit tento formulář anebo přijít již s ručně vyplněným formulářem, který při předání dítěte podepíšete.</p>
<!--                <p class="bg-danger">Omlouváme se, ale online rezervace je dočasně nedostupná. Situaci řešíme a prozatím je možné použít formulář ke stažení.</p> -->
                <a href="docs/hafik-pdf-prihlaska.pdf" title="Formulář ke stažení" download="hafik-registracni-formular.pdf"><i class="fa fa-file-pdf-o fa-fw"></i>&nbsp; FORMULÁŘ KE STAŽENÍ</a>
                <h2><strong>Online rezervace</strong></h2>
                <form id="reg-form" data-toggle="validator" role="form" method="post" action="process_registration.php">
                    <h3>Období hlídání</h3>
                    <div class="form-group">
                        <input class="date-time-from form-control" id="inputCareStart" type="text" name="careStart" value="<?php if (array_key_exists('careStart', $oldFormData)) echo $oldFormData['careStart']; ?>" placeholder="Začátek hlídání" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input class="date-time-to form-control" id="inputCareEnd" type="text" name="careEnd" value="<?php if (array_key_exists('careEnd', $oldFormData)) echo $oldFormData['careEnd']; ?>" placeholder="Konec hlídání" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="clearfix"></div>
                    <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
                    <div class="collapse" id="addMoreDates">
                        <div class="form-group">
                            <input class="date-time-from form-control" id="inputCareStart2" name="care[1][start]" type="text" value="<?php if (array_key_exists('start', $oldFormData['care'][1])) echo $oldFormData['care'][1]['start']; ?>" placeholder="Začátek hlídání">
                        </div>
                        <div class="form-group">
                            <input class="date-time-to form-control" id="inputCareEnd2" name="care[1][end]" type="text" value="<?php if (array_key_exists('end', $oldFormData['care'][1])) echo $oldFormData['care'][1]['end']; ?>" placeholder="Konec hlídání">
                        </div>
                        <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates2" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
                    </div>
                    <div class="collapse" id="addMoreDates2">
                        <div class="form-group">
                            <input class="date-time-from form-control" id="inputCareStart3" name="care[2][start]" type="text" value="<?php if (array_key_exists('start', $oldFormData['care'][2])) echo $oldFormData['care'][2]['start']; ?>" placeholder="Začátek hlídání">
                        </div>
                        <div class="form-group">
                            <input class="date-time-to form-control" id="inputCareEnd3" name="care[2][end]" type="text" value="<?php if (array_key_exists('end', $oldFormData)) echo $oldFormData['care'][2]['end']; ?>" placeholder="Konec hlídání">
                        </div>
                        <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates3" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
                    </div>
                    <div class="collapse" id="addMoreDates3">
                        <div class="form-group">
                            <input class="date-time-from form-control" id="inputCareStart4" name="care[3][start]" type="text" value="<?php if (array_key_exists('start', $oldFormData['care'][3])) echo $oldFormData['care'][3]['start']; ?>" placeholder="Začátek hlídání">
                        </div>
                        <div class="form-group">
                            <input class="date-time-to form-control" id="inputCareEnd2" name="care[3][end]" type="text" value="<?php if (array_key_exists('end', $oldFormData['care'][3])) echo $oldFormData['care'][3]['end']; ?>" placeholder="Konec hlídání">
                        </div>
                        <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates4" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
                    </div>
                    <div class="collapse" id="addMoreDates4">
                        <div class="form-group">
                            <input class="date-time-from form-control" id="inputCareStart4" name="care[4][start]" type="text" value="<?php if (array_key_exists('start', $oldFormData['care'][4])) echo $oldFormData['care'][4]['start']; ?>" placeholder="Začátek hlídání">
                        </div>
                        <div class="form-group">
                            <input class="date-time-to form-control" id="inputCareEnd4" name="care[4][end]" type="text" value="<?php if (array_key_exists('end', $oldFormData['care'][4])) echo $oldFormData['care'][4]['end']; ?>" placeholder="Konec hlídání">
                        </div>
                        <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreDates5" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další období</span><span class="rmv-date">Odebrat toto období</span></a>
                    </div>
                    <div class="collapse" id="addMoreDates5">
                        <div class="form-group">
                            <input class="date-time-from form-control" id="inputCareStart6" name="care[5][start]" type="text" value="<?php if (array_key_exists('care', $oldFormData['care'][5])) echo $oldFormData['careEnd'][5]['start']; ?>" placeholder="Začátek hlídání">
                        </div>
                        <div class="form-group">
                            <input class="date-time-to form-control" id="inputCareEnd6" name="care[5][end]" type="text" value="<?php if (array_key_exists('care', $oldFormData['care'][5])) echo $oldFormData['careEnd'][5]['start']; ?>" placeholder="Konec hlídání">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <h3>Zákonný zástupce dítěte</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputNameGuardian" name="guardianName" value="<?php if (array_key_exists('guardianName', $oldFormData)) echo $oldFormData['guardianName']; ?>" placeholder="Jméno" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputSurnameGuardian" name="guardianSurname" value="<?php if (array_key_exists('guardianSurname', $oldFormData)) echo $oldFormData['guardianSurname']; ?>" placeholder="Příjmení" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="selectSexGuardian" name="guardianSex" data-error="Toto pole je nutno vyplnit" required>
                            <option class="disabled" disabled selected>Pohlaví</option>
                            <?php
                                if (array_key_exists('guardianSex', $oldFormData)) {
                                    $guardianSex = $oldFormData['guardianSex'];

                                    if ($guardianSex == 1) {
                                        echo '<option value="1" selected>Žena</option>';
                                    } else {
                                        echo '<option value="1">Žena</option>';
                                    }
                                    if ($guardianSex == 2) {
                                        echo '<option value="2" selected>Muž</option>';
                                    } else {
                                        echo '<option value="2">Muž</option>';
                                    }
                                } else {
                                    $genders = findAllGenders();
                                    foreach ($genders as $gender) {
                                        echo "<option value=\"$gender[id]\">$gender[adult]</option>";
                                    }
                                }
                            ?>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputIDCardGuardian" name="guardianIDCard" value="<?php if (array_key_exists('guardianIDCard', $oldFormData)) echo $oldFormData['guardianIDCard']; ?>" placeholder="Číslo občanského průkazu" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputIDGuardian" name="guardianID" value="<?php if (array_key_exists('guardianID', $oldFormData)) echo $oldFormData['guardianID']; ?>" placeholder="Rodné číslo" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputPhoneGuardian" name="guardianPhone" value="<?php if (array_key_exists('guardianPhone', $oldFormData)) echo $oldFormData['guardianPhone']; ?>" placeholder="Telefon" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="inputEmailGuardian" name="guardianEmail" value="<?php if (array_key_exists('guardianEmail', $oldFormData)) echo $oldFormData['guardianEmail']; ?>" placeholder="E-mail" data-error="Neexistující e-mailová adresa" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputAddressGuardian" name="guardianAddress" value="<?php if (array_key_exists('guardianAddress', $oldFormData)) echo $oldFormData['guardianAddress']; ?>" placeholder="Adresa" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputCityGuardian" name="guardianCity" value="<?php if (array_key_exists('guardianCity', $oldFormData)) echo $oldFormData['guardianCity']; ?>" placeholder="Město" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputZIPGuardian" name="guardianZIP" value="<?php if (array_key_exists('guardianZIP', $oldFormData)) echo $oldFormData['guardianZIP']; ?>" placeholder="PSČ" data-minlength="5" data-error="Neexistující PSČ" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreGuardians" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další osoby oprávněné k vyzvednutí dítěte</span><span class="rmv-date">Odebrat další osoby oprávněné k vyzvednutí dítěte</span></a>
                    <div class="collapse" id="addMoreGuardians">
                        <h5>Další oprávněná osoba k vyzvednutí dítěte 1</h5>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputNameGuardian2" name="otherGuardians[1][name]" value="<?php if (array_key_exists('name', $oldFormData['otherGuardians'][1])) echo $oldFormData['otherGuardians'][1]['name']; ?>" placeholder="Jméno">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputSurnameGuardian2" name="otherGuardians[1][surname]" value="<?php if (array_key_exists('surname', $oldFormData['otherGuardians'][1])) echo $oldFormData['otherGuardians'][1]['surname']; ?>" placeholder="Příjmení">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputRelationshipGuardian2" name="otherGuardians[1][relationship]" value="<?php if (array_key_exists('relationship', $oldFormData['otherGuardians'][1])) echo $oldFormData['otherGuardians'][1]['relationship']; ?>" placeholder="Vztah k dítěti">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputPhoneGuardian2" name="otherGuardians[1][phone]" value="<?php if (array_key_exists('phone', $oldFormData['otherGuardians'][1])) echo $oldFormData['otherGuardians'][1]['phone']; ?>" placeholder="Telefon">
                        </div>
                        <div class="clearfix"></div>
                        <a class="btn collapsed" role="button" data-toggle="collapse" href="#addMoreGuardians2" aria-expanded="false"><i class="fa fa-plus"></i> <span class="add-date">Přidat další osoby oprávněné k vyzvednutí dítěte</span><span class="rmv-date">Odebrat další osoby oprávněné k vyzvednutí dítěte</span></a>
                    </div>
                    <div class="collapse" id="addMoreGuardians2">

                        <div class="clearfix"></div>
                        <h5>Další oprávněná osoba k vyzvednutí dítěte 2</h5>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputNameGuardian3" name="otherGuardians[2][name]" value="<?php if (array_key_exists('name', $oldFormData['otherGuardians'][2])) echo $oldFormData['otherGuardians'][2]['name']; ?>" placeholder="Jméno">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputSurnameGuardian3" name="otherGuardians[2][surname]" value="<?php if (array_key_exists('surname', $oldFormData['otherGuardians'][2])) echo $oldFormData['otherGuardians'][2]['surname']; ?>" placeholder="Příjmení">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputRelationshipGuardian3" name="otherGuardians[2][relationship]" value="<?php if (array_key_exists('relationship', $oldFormData['otherGuardians'][2])) echo $oldFormData['otherGuardians'][2]['relationship']; ?>" placeholder="Vztah k dítěti">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputPhoneGuardian3" name="otherGuardians[2][phone]" value="<?php if (array_key_exists('phone', $oldFormData['otherGuardians'][2])) echo $oldFormData['otherGuardians'][2]['phone']; ?>" placeholder="Telefon">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <h3>Informace o dítěti <label><input type="checkbox" name="sameAddress" onclick="FillAddress(this.form)"> Dítě bydlí na stejné adrese jako zákonný zástupce</label></h3>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputNameChild" name="childName" value="<?php if (array_key_exists('childName', $oldFormData)) echo $oldFormData['childName']; ?>" placeholder="Jméno" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputSurnameChild" name="childSurname" value="<?php if (array_key_exists('childSurname', $oldFormData)) echo $oldFormData['childSurname']; ?>" placeholder="Příjmení" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputIDChild" name="childID" value="<?php if (array_key_exists('childID', $oldFormData)) echo $oldFormData['childID']; ?>" placeholder="Rodné číslo" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="selectSexChild" name="childSex" data-error="Toto pole je nutno vyplnit" required>
                            <option class="disabled" disabled selected>Pohlaví</option>
                            <?php
                            if (array_key_exists('childSex', $oldFormData)) {
                                $childSex = $oldFormData['childSex'];

                                if ($childSex == 1) {
                                    echo '<option value="1" selected>Dívka</option>';
                                } else {
                                    echo '<option value="1">Dívka</option>';
                                }
                                if ($childSex == 2) {
                                    echo '<option value="2" selected>Chlapec</option>';
                                } else {
                                    echo '<option value="2">Chlapec</option>';
                                }
                            } else {
                                $genders = findAllGenders();
                                foreach ($genders as $gender) {
                                    echo "<option value=\"$gender[id]\">$gender[child]</option>";
                                }
                            }
                            ?>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group no-full">
                        <input class="date-of-birth form-control" type="text" name="childBirth" value="<?php if (array_key_exists('childBirth', $oldFormData)) echo $oldFormData['childBirth']; ?>" placeholder="Datum narození" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputAddressChild" name="childAddress" value="<?php if (array_key_exists('childAddress', $oldFormData)) echo $oldFormData['childAddress']; ?>" placeholder="Adresa" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputCityChild" name="childCity" value="<?php if (array_key_exists('childCity', $oldFormData)) echo $oldFormData['childCity']; ?>" placeholder="Město" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputZIPChild" name="childZIP" value="<?php if (array_key_exists('childZIP', $oldFormData)) echo $oldFormData['childZIP']; ?>" placeholder="PSČ" data-minlength="5" data-error="Neexistující PSČ" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="inputInsuranceChild" name="childHealthInsurance" value="<?php if (array_key_exists('childHealthInsurance', $oldFormData)) echo $oldFormData['childHealthInsurance']; ?>" placeholder="Zdravotní pojišťovna" data-error="Toto pole je nutno vyplnit" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group full">
                        <textarea data-minlength="5" rows="3" maxlength="100" type="text" class="form-control" id="inputOtherInfoChild" name="childImportantInfo" value="<?php if (array_key_exists('childImportantInfo', $oldFormData)) echo $oldFormData['childImportantInfo']; ?>" placeholder="Zdravotní stav a další důležité informace (alergie, zdravotního omezení aj.)"></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" id="checkboxPD" name="rulesApproval" data-error="Je nutné souhlasit" required> Souhlasím s <a href="javascript:void(0)" data-toggle="modal" data-target=".personal-data">pravidly Centra Hafík - hlídání dětí s.r.o.</a>
                        </label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" id="checkboxVaccination" name="vaccinationStatement" <?php if (array_key_exists('vaccinationStatement', $oldFormData)) echo 'checked' ?>> Prohlašuji, že dítě bylo očkováno proti infekčním nemocem
                        </label>
                    </div>
                    <button type="submit" class="btn">Odeslat žádost</button>
                </form> 

            </div>
        </div>
    </div>


    <!-- Modal for "Perdonal data" -->
    <div class="modal fade personal-data" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Zavřít"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Pravidla Centra Hafík - hlídání dětí s.r.o.</h4>
                </div>
                <div class="modal-body">
                    <p>V případě opakované návštěvy dítěte v Centru Hafík se rodiče zavazuji k oznamování veškerých změn údajů týkajících se pobytu dítěte v Centru Hafík (např. změna bydliště dítěte, změna kontaktů, změna osob vyzvedávajících, apod.) a oznamování veškerých změn souvisejících se změnou zdravotního stavu dítěte (např. výskyt přenosné choroby v rodině nebo nejbližším okolí dítěte apod.).</p>
                    <ul>
                        <li>Prohlašuji, že jsem se seznámil/a s Ceníkem za poskytované služby a Provozním řádem Centra Hafík – hlídání dětí s.r.o., ve kterém jsou uvedeny veškeré podrobnosti týkající se hlídací služby, a tento se zavazuji respektovat. </li>
                        <li>Prohlašuji, že všechny mnou uvedené údaje jsou pravdivé a správné. </li>
                        <li>Prohlašuji, že souhlasím s monitorováním dítěte kdykoliv po předání do Centra Hafík, jeho focením a pořízením videa a s následným umístěním videa či fotografie dítěte na webové stránky Hafíka. </li>
                        <li>Prohlašuji, že souhlasím se zpracováním osobních údajů svých a svého dítěte, pro evidenční potřeby centra Hafík </li>
                        <li>Prohlašuji, že dítě bylo očkováno proti infekčním nemocem (tedy absolvovalo povinná očkování), zejména dle vyhlášky Ministerstva zdravotnictví MZ č.537/2006 Sb.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for "Perdonal data" End -->



    <!-- Content End -->

    <?php include('inc_footer.php'); ?>
