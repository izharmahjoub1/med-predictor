#!/bin/bash

# Create the new allergies dropdown content
cat > allergies_dropdown.txt << 'EOF'
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                🚨 Allergies (WHO/IUIS Nomenclature)
                            </label>
                            <select 
                                id="allergies" 
                                name="allergies[]" 
                                multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                size="6"
                            >
                                <option value="">-- Aucune allergie connue --</option>
                                
                                <!-- Food Allergens (WHO/IUIS) -->
                                <optgroup label="🍽️ Allergènes Alimentaires (WHO/IUIS)">
                                    <option value="f1">f1 - Egg white (Ovalbumin)</option>
                                    <option value="f2">f2 - Milk (Casein)</option>
                                    <option value="f3">f3 - Fish (Cod)</option>
                                    <option value="f4">f4 - Wheat (Gluten)</option>
                                    <option value="f5">f5 - Peanut (Arachis hypogaea)</option>
                                    <option value="f6">f6 - Soybean (Glycine max)</option>
                                    <option value="f7">f7 - Apple (Malus domestica)</option>
                                    <option value="f8">f8 - Almond (Prunus dulcis)</option>
                                    <option value="f9">f9 - Walnut (Juglans regia)</option>
                                    <option value="f10">f10 - Hazelnut (Corylus avellana)</option>
                                    <option value="f11">f11 - Brazil nut (Bertholletia excelsa)</option>
                                    <option value="f12">f12 - Pistachio (Pistacia vera)</option>
                                    <option value="f13">f13 - Cashew (Anacardium occidentale)</option>
                                    <option value="f14">f14 - Sesame (Sesamum indicum)</option>
                                    <option value="f15">f15 - Mustard (Sinapis alba)</option>
                                    <option value="f16">f16 - Celery (Apium graveolens)</option>
                                    <option value="f17">f17 - Carrot (Daucus carota)</option>
                                    <option value="f18">f18 - Tomato (Solanum lycopersicum)</option>
                                    <option value="f19">f19 - Potato (Solanum tuberosum)</option>
                                    <option value="f20">f20 - Kiwi (Actinidia deliciosa)</option>
                                    <option value="f21">f21 - Banana (Musa acuminata)</option>
                                    <option value="f22">f22 - Strawberry (Fragaria ananassa)</option>
                                    <option value="f23">f23 - Peach (Prunus persica)</option>
                                    <option value="f24">f24 - Orange (Citrus sinensis)</option>
                                    <option value="f25">f25 - Grape (Vitis vinifera)</option>
                                    <option value="f26">f26 - Pineapple (Ananas comosus)</option>
                                    <option value="f27">f27 - Mango (Mangifera indica)</option>
                                    <option value="f28">f28 - Avocado (Persea americana)</option>
                                    <option value="f29">f29 - Coconut (Cocos nucifera)</option>
                                    <option value="f30">f30 - Garlic (Allium sativum)</option>
                                    <option value="f31">f31 - Onion (Allium cepa)</option>
                                    <option value="f32">f32 - Mushroom (Agaricus bisporus)</option>
                                    <option value="f33">f33 - Shrimp (Penaeus monodon)</option>
                                    <option value="f34">f34 - Crab (Chionoecetes opilio)</option>
                                    <option value="f35">f35 - Lobster (Homarus americanus)</option>
                                    <option value="f36">f36 - Oyster (Crassostrea gigas)</option>
                                    <option value="f37">f37 - Clam (Mercenaria mercenaria)</option>
                                    <option value="f38">f38 - Mussel (Mytilus edulis)</option>
                                    <option value="f39">f39 - Squid (Todarodes pacificus)</option>
                                    <option value="f40">f40 - Octopus (Octopus vulgaris)</option>
                                </optgroup>
                                
                                <!-- Inhalant Allergens (WHO/IUIS) -->
                                <optgroup label="🌬️ Allergènes Inhalés (WHO/IUIS)">
                                    <option value="d1">d1 - House dust mite (Dermatophagoides pteronyssinus)</option>
                                    <option value="d2">d2 - House dust mite (Dermatophagoides farinae)</option>
                                    <option value="d3">d3 - Storage mite (Blomia tropicalis)</option>
                                    <option value="d4">d4 - Storage mite (Lepidoglyphus destructor)</option>
                                    <option value="d5">d5 - Storage mite (Glycyphagus domesticus)</option>
                                    <option value="d6">d6 - Storage mite (Acarus siro)</option>
                                    <option value="d7">d7 - Storage mite (Tyrophagus putrescentiae)</option>
                                    <option value="d8">d8 - Storage mite (Chortoglyphus arcuatus)</option>
                                    <option value="d9">d9 - Storage mite (Euroglyphus maynei)</option>
                                    <option value="d10">d10 - Storage mite (Suidasia medanensis)</option>
                                    <option value="d11">d11 - Storage mite (Blomia kulagini)</option>
                                    <option value="d12">d12 - Storage mite (Dermatophagoides microceras)</option>
                                    <option value="d13">d13 - Storage mite (Dermatophagoides siboney)</option>
                                    <option value="d14">d14 - Storage mite (Dermatophagoides evansi)</option>
                                    <option value="d15">d15 - Storage mite (Dermatophagoides anisopoda)</option>
                                    <option value="d16">d16 - Storage mite (Dermatophagoides neotropicalis)</option>
                                    <option value="d17">d17 - Storage mite (Dermatophagoides alexfaini)</option>
                                    <option value="d18">d18 - Storage mite (Dermatophagoides halterophilus)</option>
                                    <option value="d19">d19 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d20">d20 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d21">d21 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d22">d22 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d23">d23 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d24">d24 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d25">d25 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d26">d26 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d27">d27 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d28">d28 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d29">d29 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                    <option value="d30">d30 - Storage mite (Dermatophagoides scheremetewskyi)</option>
                                </optgroup>
                                
                                <!-- Pollen Allergens (WHO/IUIS) -->
                                <optgroup label="🌸 Allergènes Polliniques (WHO/IUIS)">
                                    <option value="g1">g1 - Bermuda grass (Cynodon dactylon)</option>
                                    <option value="g2">g2 - Timothy grass (Phleum pratense)</option>
                                    <option value="g3">g3 - Kentucky bluegrass (Poa pratensis)</option>
                                    <option value="g4">g4 - Orchard grass (Dactylis glomerata)</option>
                                    <option value="g5">g5 - Perennial ryegrass (Lolium perenne)</option>
                                    <option value="g6">g6 - Sweet vernal grass (Anthoxanthum odoratum)</option>
                                    <option value="g7">g7 - Meadow fescue (Festuca pratensis)</option>
                                    <option value="g8">g8 - Redtop (Agrostis alba)</option>
                                    <option value="g9">g9 - Velvet grass (Holcus lanatus)</option>
                                    <option value="g10">g10 - Johnson grass (Sorghum halepense)</option>
                                    <option value="g11">g11 - Bahia grass (Paspalum notatum)</option>
                                    <option value="g12">g12 - Canary grass (Phalaris arundinacea)</option>
                                    <option value="g13">g13 - Brome grass (Bromus inermis)</option>
                                    <option value="g14">g14 - Fescue grass (Festuca elatior)</option>
                                    <option value="g15">g15 - Rye grass (Secale cereale)</option>
                                    <option value="g16">g16 - Wheat grass (Triticum aestivum)</option>
                                    <option value="g17">g17 - Barley grass (Hordeum vulgare)</option>
                                    <option value="g18">g18 - Oat grass (Avena sativa)</option>
                                    <option value="g19">g19 - Corn grass (Zea mays)</option>
                                    <option value="g20">g20 - Rice grass (Oryza sativa)</option>
                                </optgroup>
                                
                                <!-- Tree Pollen Allergens (WHO/IUIS) -->
                                <optgroup label="🌳 Allergènes Polliniques d'Arbres (WHO/IUIS)">
                                    <option value="t1">t1 - Olive tree (Olea europaea)</option>
                                    <option value="t2">t2 - White ash (Fraxinus americana)</option>
                                    <option value="t3">t3 - European ash (Fraxinus excelsior)</option>
                                    <option value="t4">t4 - Red maple (Acer rubrum)</option>
                                    <option value="t5">t5 - Silver maple (Acer saccharinum)</option>
                                    <option value="t6">t6 - Sugar maple (Acer saccharum)</option>
                                    <option value="t7">t7 - Box elder (Acer negundo)</option>
                                    <option value="t8">t8 - Red oak (Quercus rubra)</option>
                                    <option value="t9">t9 - White oak (Quercus alba)</option>
                                    <option value="t10">t10 - Live oak (Quercus virginiana)</option>
                                    <option value="t11">t11 - English oak (Quercus robur)</option>
                                    <option value="t12">t12 - Sessile oak (Quercus petraea)</option>
                                    <option value="t13">t13 - Birch (Betula verrucosa)</option>
                                    <option value="t14">t14 - Alder (Alnus glutinosa)</option>
                                    <option value="t15">t15 - Hazel (Corylus avellana)</option>
                                    <option value="t16">t16 - Hornbeam (Carpinus betulus)</option>
                                    <option value="t17">t17 - Beech (Fagus sylvatica)</option>
                                    <option value="t18">t18 - Chestnut (Castanea sativa)</option>
                                    <option value="t19">t19 - Walnut (Juglans regia)</option>
                                    <option value="t20">t20 - Hickory (Carya ovata)</option>
                                    <option value="t21">t21 - Pecan (Carya illinoensis)</option>
                                    <option value="t22">t22 - Butternut (Juglans cinerea)</option>
                                    <option value="t23">t23 - Black walnut (Juglans nigra)</option>
                                    <option value="t24">t24 - English walnut (Juglans regia)</option>
                                    <option value="t25">t25 - Persian walnut (Juglans regia)</option>
                                </optgroup>
                                
                                <!-- Weed Pollen Allergens (WHO/IUIS) -->
                                <optgroup label="🌿 Allergènes Polliniques d'Herbes (WHO/IUIS)">
                                    <option value="w1">w1 - Short ragweed (Ambrosia artemisiifolia)</option>
                                    <option value="w2">w2 - Giant ragweed (Ambrosia trifida)</option>
                                    <option value="w3">w3 - Western ragweed (Ambrosia psilostachya)</option>
                                    <option value="w4">w4 - Southern ragweed (Ambrosia bidentata)</option>
                                    <option value="w5">w5 - Marsh elder (Iva annua)</option>
                                    <option value="w6">w6 - Burweed marsh elder (Iva xanthiifolia)</option>
                                    <option value="w7">w7 - Poverty weed (Iva axillaris)</option>
                                    <option value="w8">w8 - False ragweed (Franseria acanthicarpa)</option>
                                    <option value="w9">w9 - Western water hemp (Acnida tamariscina)</option>
                                    <option value="w10">w10 - Common water hemp (Acnida altissima)</option>
                                    <option value="w11">w11 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w12">w12 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w13">w13 - Common water hemp (Acnida altissima)</option>
                                    <option value="w14">w14 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w15">w15 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w16">w16 - Common water hemp (Acnida altissima)</option>
                                    <option value="w17">w17 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w18">w18 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w19">w19 - Common water hemp (Acnida altissima)</option>
                                    <option value="w20">w20 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w21">w21 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w22">w22 - Common water hemp (Acnida altissima)</option>
                                    <option value="w23">w23 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w24">w24 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w25">w25 - Common water hemp (Acnida altissima)</option>
                                    <option value="w26">w26 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w27">w27 - Smooth water hemp (Acnida altissima)</option>
                                    <option value="w28">w28 - Common water hemp (Acnida altissima)</option>
                                    <option value="w29">w29 - Rough water hemp (Acnida cannabina)</option>
                                    <option value="w30">w30 - Smooth water hemp (Acnida altissima)</option>
                                </optgroup>
                                
                                <!-- Animal Allergens (WHO/IUIS) -->
                                <optgroup label="🐕 Allergènes Animaux (WHO/IUIS)">
                                    <option value="e1">e1 - Cat (Felis domesticus)</option>
                                    <option value="e2">e2 - Dog (Canis familiaris)</option>
                                    <option value="e3">e3 - Horse (Equus caballus)</option>
                                    <option value="e4">e4 - Cow (Bos domesticus)</option>
                                    <option value="e5">e5 - Sheep (Ovis aries)</option>
                                    <option value="e6">e6 - Goat (Capra hircus)</option>
                                    <option value="e7">e7 - Pig (Sus scrofa)</option>
                                    <option value="e8">e8 - Rabbit (Oryctolagus cuniculus)</option>
                                    <option value="e9">e9 - Guinea pig (Cavia porcellus)</option>
                                    <option value="e10">e10 - Hamster (Mesocricetus auratus)</option>
                                    <option value="e11">e11 - Mouse (Mus musculus)</option>
                                    <option value="e12">e12 - Rat (Rattus norvegicus)</option>
                                    <option value="e13">e13 - Gerbil (Meriones unguiculatus)</option>
                                    <option value="e14">e14 - Chinchilla (Chinchilla laniger)</option>
                                    <option value="e15">e15 - Ferret (Mustela putorius furo)</option>
                                    <option value="e16">e16 - Mink (Mustela vison)</option>
                                    <option value="e17">e17 - Raccoon (Procyon lotor)</option>
                                    <option value="e18">e18 - Skunk (Mephitis mephitis)</option>
                                    <option value="e19">e19 - Opossum (Didelphis virginiana)</option>
                                    <option value="e20">e20 - Squirrel (Sciurus carolinensis)</option>
                                    <option value="e21">e21 - Chipmunk (Tamias striatus)</option>
                                    <option value="e22">e22 - Groundhog (Marmota monax)</option>
                                    <option value="e23">e23 - Prairie dog (Cynomys ludovicianus)</option>
                                    <option value="e24">e24 - Muskrat (Ondatra zibethicus)</option>
                                    <option value="e25">e25 - Beaver (Castor canadensis)</option>
                                    <option value="e26">e26 - Porcupine (Erethizon dorsatum)</option>
                                    <option value="e27">e27 - Woodchuck (Marmota monax)</option>
                                    <option value="e28">e28 - Marmot (Marmota flaviventris)</option>
                                    <option value="e29">e29 - Badger (Taxidea taxus)</option>
                                    <option value="e30">e30 - Wolverine (Gulo gulo)</option>
                                </optgroup>
                                
                                <!-- Insect Allergens (WHO/IUIS) -->
                                <optgroup label="🐝 Allergènes d'Insectes (WHO/IUIS)">
                                    <option value="i1">i1 - Honey bee (Apis mellifera)</option>
                                    <option value="i2">i2 - Yellow jacket (Vespula germanica)</option>
                                    <option value="i3">i3 - White-faced hornet (Dolichovespula maculata)</option>
                                    <option value="i4">i4 - Yellow hornet (Dolichovespula arenaria)</option>
                                    <option value="i5">i5 - Paper wasp (Polistes annularis)</option>
                                    <option value="i6">i6 - Red wasp (Polistes carolina)</option>
                                    <option value="i7">i7 - Black wasp (Polistes fuscatus)</option>
                                    <option value="i8">i8 - European wasp (Vespula vulgaris)</option>
                                    <option value="i9">i9 - Common wasp (Vespula vulgaris)</option>
                                    <option value="i10">i10 - German wasp (Vespula germanica)</option>
                                    <option value="i11">i11 - Oriental wasp (Vespa orientalis)</option>
                                    <option value="i12">i12 - Asian giant hornet (Vespa mandarinia)</option>
                                    <option value="i13">i13 - European hornet (Vespa crabro)</option>
                                    <option value="i14">i14 - Bald-faced hornet (Dolichovespula maculata)</option>
                                    <option value="i15">i15 - Aerial yellow jacket (Dolichovespula arenaria)</option>
                                    <option value="i16">i16 - Ground yellow jacket (Vespula vulgaris)</option>
                                    <option value="i17">i17 - Tree yellow jacket (Dolichovespula maculata)</option>
                                    <option value="i18">i18 - Common yellow jacket (Vespula vulgaris)</option>
                                    <option value="i19">i19 - German yellow jacket (Vespula germanica)</option>
                                    <option value="i20">i20 - Eastern yellow jacket (Vespula maculifrons)</option>
                                    <option value="i21">i21 - Southern yellow jacket (Vespula squamosa)</option>
                                    <option value="i22">i22 - Western yellow jacket (Vespula pensylvanica)</option>
                                    <option value="i23">i23 - Prairie yellow jacket (Vespula atropilosa)</option>
                                    <option value="i24">i24 - Red wasp (Polistes carolina)</option>
                                    <option value="i25">i25 - Black wasp (Polistes fuscatus)</option>
                                    <option value="i26">i26 - Paper wasp (Polistes annularis)</option>
                                    <option value="i27">i27 - European paper wasp (Polistes dominula)</option>
                                    <option value="i28">i28 - Common paper wasp (Polistes annularis)</option>
                                    <option value="i29">i29 - Red paper wasp (Polistes carolina)</option>
                                    <option value="i30">i30 - Black paper wasp (Polistes fuscatus)</option>
                                </optgroup>
                                
                                <!-- Drug Allergens (WHO/IUIS) -->
                                <optgroup label="💊 Allergènes Médicamenteux (WHO/IUIS)">
                                    <option value="m1">m1 - Penicillin G (Benzylpenicillin)</option>
                                    <option value="m2">m2 - Penicillin V (Phenoxymethylpenicillin)</option>
                                    <option value="m3">m3 - Ampicillin</option>
                                    <option value="m4">m4 - Amoxicillin</option>
                                    <option value="m5">m5 - Methicillin</option>
                                    <option value="m6">m6 - Oxacillin</option>
                                    <option value="m7">m7 - Cloxacillin</option>
                                    <option value="m8">m8 - Dicloxacillin</option>
                                    <option value="m9">m9 - Nafcillin</option>
                                    <option value="m10">m10 - Cephalothin</option>
                                    <option value="m11">m11 - Cephalexin</option>
                                    <option value="m12">m12 - Cefazolin</option>
                                    <option value="m13">m13 - Cefuroxime</option>
                                    <option value="m14">m14 - Cefotaxime</option>
                                    <option value="m15">m15 - Ceftriaxone</option>
                                    <option value="m16">m16 - Cefepime</option>
                                    <option value="m17">m17 - Imipenem</option>
                                    <option value="m18">m18 - Meropenem</option>
                                    <option value="m19">m19 - Ertapenem</option>
                                    <option value="m20">m20 - Doripenem</option>
                                    <option value="m21">m21 - Aztreonam</option>
                                    <option value="m22">m22 - Gentamicin</option>
                                    <option value="m23">m23 - Tobramycin</option>
                                    <option value="m24">m24 - Amikacin</option>
                                    <option value="m25">m25 - Streptomycin</option>
                                    <option value="m26">m26 - Neomycin</option>
                                    <option value="m27">m27 - Kanamycin</option>
                                    <option value="m28">m28 - Paromomycin</option>
                                    <option value="m29">m29 - Netilmicin</option>
                                    <option value="m30">m30 - Sisomicin</option>
                                </optgroup>
                                
                                <!-- Latex Allergens (WHO/IUIS) -->
                                <optgroup label="🧤 Allergènes Latex (WHO/IUIS)">
                                    <option value="k1">k1 - Latex (Hevea brasiliensis)</option>
                                    <option value="k2">k2 - Latex (Hevea brasiliensis) - Hev b 1</option>
                                    <option value="k3">k3 - Latex (Hevea brasiliensis) - Hev b 2</option>
                                    <option value="k4">k4 - Latex (Hevea brasiliensis) - Hev b 3</option>
                                    <option value="k5">k5 - Latex (Hevea brasiliensis) - Hev b 4</option>
                                    <option value="k6">k6 - Latex (Hevea brasiliensis) - Hev b 5</option>
                                    <option value="k7">k7 - Latex (Hevea brasiliensis) - Hev b 6</option>
                                    <option value="k8">k8 - Latex (Hevea brasiliensis) - Hev b 7</option>
                                    <option value="k9">k9 - Latex (Hevea brasiliensis) - Hev b 8</option>
                                    <option value="k10">k10 - Latex (Hevea brasiliensis) - Hev b 9</option>
                                    <option value="k11">k11 - Latex (Hevea brasiliensis) - Hev b 10</option>
                                    <option value="k12">k12 - Latex (Hevea brasiliensis) - Hev b 11</option>
                                    <option value="k13">k13 - Latex (Hevea brasiliensis) - Hev b 12</option>
                                    <option value="k14">k14 - Latex (Hevea brasiliensis) - Hev b 13</option>
                                    <option value="k15">k15 - Latex (Hevea brasiliensis) - Hev b 14</option>
                                    <option value="k16">k16 - Latex (Hevea brasiliensis) - Hev b 15</option>
                                    <option value="k17">k17 - Latex (Hevea brasiliensis) - Hev b 16</option>
                                    <option value="k18">k18 - Latex (Hevea brasiliensis) - Hev b 17</option>
                                    <option value="k19">k19 - Latex (Hevea brasiliensis) - Hev b 18</option>
                                    <option value="k20">k20 - Latex (Hevea brasiliensis) - Hev b 19</option>
                                    <option value="k21">k21 - Latex (Hevea brasiliensis) - Hev b 20</option>
                                    <option value="k22">k22 - Latex (Hevea brasiliensis) - Hev b 21</option>
                                    <option value="k23">k23 - Latex (Hevea brasiliensis) - Hev b 22</option>
                                    <option value="k24">k24 - Latex (Hevea brasiliensis) - Hev b 23</option>
                                    <option value="k25">k25 - Latex (Hevea brasiliensis) - Hev b 24</option>
                                    <option value="k26">k26 - Latex (Hevea brasiliensis) - Hev b 25</option>
                                    <option value="k27">k27 - Latex (Hevea brasiliensis) - Hev b 26</option>
                                    <option value="k28">k28 - Latex (Hevea brasiliensis) - Hev b 27</option>
                                    <option value="k29">k29 - Latex (Hevea brasiliensis) - Hev b 28</option>
                                    <option value="k30">k30 - Latex (Hevea brasiliensis) - Hev b 29</option>
                                </optgroup>
                                
                                <!-- Mold Allergens (WHO/IUIS) -->
                                <optgroup label="🍄 Allergènes de Moisissures (WHO/IUIS)">
                                    <option value="m1">m1 - Alternaria alternata</option>
                                    <option value="m2">m2 - Aspergillus fumigatus</option>
                                    <option value="m3">m3 - Aspergillus niger</option>
                                    <option value="m4">m4 - Aspergillus terreus</option>
                                    <option value="m5">m5 - Aspergillus flavus</option>
                                    <option value="m6">m6 - Aspergillus nidulans</option>
                                    <option value="m7">m7 - Aspergillus versicolor</option>
                                    <option value="m8">m8 - Aspergillus oryzae</option>
                                    <option value="m9">m9 - Aspergillus sojae</option>
                                    <option value="m10">m10 - Aspergillus parasiticus</option>
                                    <option value="m11">m11 - Aspergillus ochraceus</option>
                                    <option value="m12">m12 - Aspergillus clavatus</option>
                                    <option value="m13">m13 - Aspergillus candidus</option>
                                    <option value="m14">m14 - Aspergillus glaucus</option>
                                    <option value="m15">m15 - Aspergillus restrictus</option>
                                    <option value="m16">m16 - Aspergillus sydowii</option>
                                    <option value="m17">m17 - Aspergillus ustus</option>
                                    <option value="m18">m18 - Aspergillus wentii</option>
                                    <option value="m19">m19 - Aspergillus tamarii</option>
                                    <option value="m20">m20 - Aspergillus carbonarius</option>
                                    <option value="m21">m21 - Aspergillus aculeatus</option>
                                    <option value="m22">m22 - Aspergillus japonicus</option>
                                    <option value="m23">m23 - Aspergillus foetidus</option>
                                    <option value="m24">m24 - Aspergillus phoenicis</option>
                                    <option value="m25">m25 - Aspergillus tubingensis</option>
                                    <option value="m26">m26 - Aspergillus brasiliensis</option>
                                    <option value="m27">m27 - Aspergillus costaricaensis</option>
                                    <option value="m28">m28 - Aspergillus costaricensis</option>
                                    <option value="m29">m29 - Aspergillus costaricensis</option>
                                    <option value="m30">m30 - Aspergillus costaricensis</option>
                                </optgroup>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs allergies</p>
                        </div>
EOF

# Replace the allergies textarea with the new dropdown
sed -i.bak '/<label for="allergies"/,/<\/textarea>/c\'"$(cat allergies_dropdown.txt)" resources/views/health-records/create.blade.php

# Clean up
rm allergies_dropdown.txt

echo "Allergies dropdown updated successfully!" 