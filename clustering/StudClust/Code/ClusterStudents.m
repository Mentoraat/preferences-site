rng(1);

fid = fopen('Dbelbin.csv','r');
l = fgetl(fid);
colnames = regexp(l,' ','split');
ncols = length(colnames);
Dbelbin = textscan(fid,repmat('%d',1,ncols),'delimiter',',','collectoutput',true);
fclose(fid);
Dbelbin = Dbelbin{1};

fid = fopen('Dpref.csv','r');
l = fgetl(fid);
studentnames = regexp(l,' ','split');
ncols = length(studentnames);
Dpref = textscan(fid,repmat('%d',1,ncols),'delimiter',',','collectoutput',true);
fclose(fid);
Dpref = Dpref{1};


nStudents = size(Dpref,1);
% fitnessfcn = @(x,Dpref,Dbelbin)ClustStudFit;
nClust = floor(nStudents / 11);
MaxMutation = 4;

%%%%%%%%%%%%%%%%%%

options = gaoptimset(@ga);
options = gaoptimset(options,...
    'PopulationSize',[30 30 30 30 30 30],...
    'EliteCount',ceil(30/100),...
    'CreationFcn',{@ClustStudCreate,nClust},...
    'CrossoverFcn',@ClustStudCrossover,...
    'MutationFcn',{@ClustStudMut,MaxMutation},...
    'PlotFcn',{@gaplotbestf,@gaplotscores},...
    'PlotInterval',3,...
    'StallGenLimit',500,...
    'TolFun',0,...
    'Generations',1000);
    
IntCon = 1:nStudents;
nvars = nStudents;

[x,fval,exitflag] = ga({@ClustStudFit,Dpref,Dbelbin},nvars,[],[],[],[],...
    [],[],[],[],options);

[xs,Is] = sort(x);

fid = fopen('output.csv', 'wt');
csvFun = @(str)sprintf('%s,',str);
xchar = cellfun(csvFun, studentnames, 'UniformOutput', false);
xchar = strcat(xchar{:});
xchar = strcat(xchar(1:end-1),'\n');
fprintf(fid,xchar);
fprintf(fid, num2str(x, '%d,'));
fclose(fid);

snsort = studentnames(Is);
Dprefsort = Dpref(Is,Is);
Dbelbinsort = Dbelbin(Is,:);
close all
imagesc(Dprefsort)
figure();
imagesc(Dbelbinsort)

figure();
for i = 1:nClust
    subplot(5,5,i);
    imagesc(Dprefsort(xs==i,xs==i));
    set(gca,'xtick',[],'ytick',[]);
end

figure();
for i = 1:nClust
    subplot(5,5,i);
    bar(sum(Dbelbin(xs==i,:)>0),'horizontal','on');
    set(gca,'yticklabel',colnames);
    set(gca,'fontsize',6)
end



