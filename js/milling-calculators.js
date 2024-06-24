
// Varibles for calculators
const sfmResult = document.getElementById('sfm-result')
const sfmRPM = document.getElementById('sfm-rpm')
const sfmDia = document.getElementById('sfm-dia')

const rpmResult = document.getElementById('rpm-result')
const rpmSFM = document.getElementById('rpm-sfm')
const rpmDia = document.getElementById('rpm-dia')

const ipmResult = document.getElementById('ipm-result')
const ipmRPM = document.getElementById('ipm-rpm')
const ipmIPT = document.getElementById('ipm-ipt')
const ipmFlutes = document.getElementById('ipm-flutes')

const iptResult = document.getElementById('ipt-result')
const iptIPM = document.getElementById('ipt-ipm')
const iptRPM = document.getElementById('ipt-rpm')
const iptFlutes = document.getElementById('ipt-flutes')

const iprResult = document.getElementById('ipr-result')
const iprIPM = document.getElementById('ipr-ipm')
const iprRPM = document.getElementById('ipr-rpm')

const mrResult = document.getElementById('mr-result')
const mrADC = document.getElementById('mr-adc')
const mrRDC = document.getElementById('mr-rdc')
const mrIPM = document.getElementById('mr-ipm')

const hpResult = document.getElementById('hp-result')
const hpADC = document.getElementById('hp-adc')
const hpRDC = document.getElementById('hp-rdc')
const hpIPM = document.getElementById('hp-ipm')
const hpPC = document.getElementById('hp-pc')

const sfmCalc = document.querySelector('#sfm-calc .calc-btn')
const rpmCalc = document.querySelector('#rpm-calc .calc-btn')
const ipmCalc = document.querySelector('#ipm-calc .calc-btn')
const iptCalc = document.querySelector('#ipt-calc .calc-btn')
const iprCalc = document.querySelector('#ipr-calc .calc-btn')
const mrCalc = document.querySelector('#mr-calc .calc-btn')
const hpCalc = document.querySelector('#hp-calc .calc-btn')
// console.log(sfmCalc)

const calculator = (event) => {
    //formulas run based on which calculator button is clicked
    if (event.target == sfmCalc) {
        let sfmValue
        value = sfmRPM.value / 3.82 * sfmDia.value
        sfmValue = value.toFixed(3)
        sfmResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + sfmValue + '</p>'
        
    } else if (event.target == rpmCalc) {
        let rpmValue
        value = rpmSFM.value * 3.82 / rpmDia.value
        rpmResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    } else if (event.target == ipmCalc) {
        let ipmValue
        value = ipmRPM.value * ipmIPT.value * ipmFlutes.value
        ipmResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    } else if (event.target == iptCalc) {
        let iptValue
        value = iptIPM.value / iptRPM.value / iptFlutes.value
        iptResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    } else if (event.target == iprCalc) {
        let iprValue
        value = iprIPM.value / iprRPM.value
        iprResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    } else if (event.target == mrCalc) {
        let mrValue
        value = mrADC.value * mrRDC.value * mrIPM.value
        mrResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    } else if (event.target == hpCalc) {
        let hpValue
        value = hpIPM.value * hpRDC.value * hpADC.value * hpPC.value
        hpResult.innerHTML = '<p class="animate__animated animate__fadeInRight">' + value.toFixed(3) + '</p>'
    }


}

sfmCalc.onclick = calculator
rpmCalc.onclick = calculator
ipmCalc.onclick = calculator
iptCalc.onclick = calculator
iprCalc.onclick = calculator
mrCalc.onclick = calculator
hpCalc.onclick = calculator


