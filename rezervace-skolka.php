<?php
$title = 'Rezervaceace | Hafík - hlídání dětí v Kutné Hoře';
$menu = '3'; // 0 or an empty string for nothing highlighted

include('inc_header.php');
require_once 'db_queries.php';

?>


    <!-- Breadcrumb -->

    <section class="sub-nav">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="index.php">Úvod</a></li>
                    <li class="active">Přihláška do školky</li>
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

                    <h1>Registrace k docházce do mateřské školy</h1>
                    <p>Pro informace o registraci k docházce do mateřské školy nás prosím kontaktujte na níže uvedeném emailu nebo telefonním čísle.</p>
                    <ul class="list-unstyled">
                        <li><strong>Telefon:</strong> 604 787 347</li>
                        <li><strong>Email:</strong> info@skolkahafik.cz</li>
                    </ul>
                    <a href="docs/prihlaska_do_skolky_hafik.pdf" title="Formulář ke stažení" download="prihlaska_do_skolky_hafik.pdf"><i class="fa fa-file-pdf-o fa-fw"></i>&nbsp; FORMULÁŘ KE STAŽENÍ</a>
                    <h2><strong>Online registrace do školky</strong></h2>
                    <form id="reg-form" data-toggle="validator" role="form" method="post" action="process_application.php">
                        <h3>Informace o rodičích</h3>
                        <p><strong>Matka</strong></p>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputNameMother" name="motherName" value="<?php if (array_key_exists('motherName', $oldFormData)) echo $oldFormData['motherName']; ?>" placeholder="Jméno" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputSurnameMother" name="motherSurname" value="<?php if (array_key_exists('motherSurname', $oldFormData)) echo $oldFormData['motherSurname']; ?>" placeholder="Příjmení" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputIDCardMother" name="motherIDCard" value="<?php if (array_key_exists('motherIDCard', $oldFormData)) echo $oldFormData['motherIDCard']; ?>" placeholder="Číslo občanského průkazu" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputIDMother" name="motherID" value="<?php if (array_key_exists('motherID', $oldFormData)) echo $oldFormData['motherID']; ?>" placeholder="Rodné číslo" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputPhoneMother" name="motherPhone" value="<?php if (array_key_exists('motherPhone', $oldFormData)) echo $oldFormData['motherPhone']; ?>" placeholder="Telefon" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="inputEmailMother" name="motherEmail" value="<?php if (array_key_exists('motherEmail', $oldFormData)) echo $oldFormData['motherEmail']; ?>" placeholder="E-mail" data-error="Neexistující e-mailová adresa" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputAddressMother" name="motherAddress" value="<?php if (array_key_exists('motherAddress', $oldFormData)) echo $oldFormData['motherAddress']; ?>" placeholder="Adresa" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputCityMother" name="motherCity" value="<?php if (array_key_exists('motherCity', $oldFormData)) echo $oldFormData['motherCity']; ?>" placeholder="Město" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputZIPMother" name="motherZIP" value="<?php if (array_key_exists('motherZIP', $oldFormData)) echo $oldFormData['motherZIP']; ?>" placeholder="PSČ" data-minlength="5" data-error="Neexistující PSČ" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="clearfix"></div>
                        <p>
                          <strong>Otec</strong>
                        </p>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputNameFather" name="fatherName" value="<?php if (array_key_exists('fatherName', $oldFormData)) echo $oldFormData['fatherName']; ?>" placeholder="Jméno" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputSurnameFather" name="fatherSurname" value="<?php if (array_key_exists('fatherSurname', $oldFormData)) echo $oldFormData['fatherSurname']; ?>" placeholder="Příjmení" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputIDCardFather" name="fatherIDCard" value="<?php if (array_key_exists('fatherIDCard', $oldFormData)) echo $oldFormData['fatherIDCard']; ?>" placeholder="Číslo občanského průkazu" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputIDFather" name="fatherID" value="<?php if (array_key_exists('fatherID', $oldFormData)) echo $oldFormData['fatherID']; ?>" placeholder="Rodné číslo" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputPhoneFather" name="fatherPhone" value="<?php if (array_key_exists('fatherPhone', $oldFormData)) echo $oldFormData['fatherPhone']; ?>" placeholder="Telefon" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="inputEmailFather" name="fatherEmail" value="<?php if (array_key_exists('fatherEmail', $oldFormData)) echo $oldFormData['fatherEmail']; ?>" placeholder="E-mail" data-error="Neexistující e-mailová adresa" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputAddressFather" name="fatherAddress" value="<?php if (array_key_exists('fatherAddress', $oldFormData)) echo $oldFormData['fatherAddress']; ?>" placeholder="Adresa" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputCityFather" name="fatherCity" value="<?php if (array_key_exists('fatherCity', $oldFormData)) echo $oldFormData['fatherCity']; ?>" placeholder="Město" data-error="Toto pole je nutno vyplnit" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="inputZIPFather" name="fatherZIP" value="<?php if (array_key_exists('fatherZIP', $oldFormData)) echo $oldFormData['fatherZIP']; ?>" placeholder="PSČ" data-minlength="5" data-error="Neexistující PSČ" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="clearfix"></div>
                        <h3>Informace o dítěti</h3>
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
                        <div class="form-group full">
                            <div class="col-md-4">
                                <h4>Celodenní docházka</h4>
                                <label><input type="checkbox" name="wholeDay[0]"> PO</label>
                                <label><input type="checkbox" name="wholeDay[1]"> ÚT</label>
                                <label><input type="checkbox" name="wholeDay[2]"> ST</label>
                                <label><input type="checkbox" name="wholeDay[3]"> ČT</label>
                                <label><input type="checkbox" name="wholeDay[4]"> PÁ</label>
                            </div>
                            <div class="col-md-4">
                                <h4>Dopolední docházka</h4>
                                <label><input type="checkbox" name="morning[0]"> PO</label>
                                <label><input type="checkbox" name="morning[1]"> ÚT</label>
                                <label><input type="checkbox" name="morning[2]"> ST</label>
                                <label><input type="checkbox" name="morning[3]"> ČT</label>
                                <label><input type="checkbox" name="morning[4]"> PÁ</label>
                            </div>
                            <div class="col-md-4">
                                <h4>Odpolední docházka</h4>
                                <label><input type="checkbox" name="afternoon[0]"> PO</label>
                                <label><input type="checkbox" name="afternoon[1]"> ÚT</label>
                                <label><input type="checkbox" name="afternoon[2]"> ST</label>
                                <label><input type="checkbox" name="afternoon[3]"> ČT</label>
                                <label><input type="checkbox" name="afternoon[4]"> PÁ</label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
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
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" id="checkboxVaccination" name="vaccinationStatement" <?php if (array_key_exists( 'vaccinationStatement', $oldFormData)) echo 'checked' ?>> Prohlašuji, že dítě bylo očkováno proti infekčním nemocem
                            </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" id="checkboxPD" name="rulesApproval" data-error="Je nutné souhlasit" required> Souhlasím s <a href="javascript:void(0)" data-toggle="modal" data-target=".personal-data">pravidly Hafík - dětské centrum, z.s.</a>
                            </label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <button type="submit" class="btn ladda-button" data-style="zoom-in" data-plugin="laddaProgress"><span class="ladda-label">Odeslat žádost</span></button>
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
                    <h4 class="modal-title">Pravidla Hafík - dětské centrum, z.s.</h4>
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
